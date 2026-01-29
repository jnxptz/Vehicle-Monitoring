<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    /**
     * Generate a unique call-of number for a maintenance record.
     *
     * Format: MN-YYYYMMDD-XXXXXX
     */
    private function generateUniqueCallOfNo(): string
    {
        $datePart = now()->format('Ymd');

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $randomPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $candidate = "MN-{$datePart}-{$randomPart}";

            if (!Maintenance::where('call_of_no', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'MN-' . now()->format('YmdHisv') . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $user = Auth::user();

        $query = Maintenance::with('vehicle');

        // Boardmembers can only see maintenances for their own vehicle(s)
        if ($user && $user->role === 'boardmember') {
            $query->whereHas('vehicle', function ($q) use ($user) {
                $q->where('bm_id', $user->id);
            });
        }

        $maintenances = $query->latest()->get();
        return view('maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        return view('maintenances.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type' => 'required|in:preventive,repair',
            'maintenance_km' => 'required|integer|min:0',
            'operation' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'conduct' => 'required|string',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $validated;
        $data['call_of_no'] = $this->generateUniqueCallOfNo();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('maintenance_photos', 'public');
        }

        Maintenance::create($data);
        return redirect()->route('maintenances.index')->with('success', 'Maintenance recorded');
    }

    public function exportPDF($id)
    {
        $maintenance = Maintenance::with(['vehicle', 'vehicle.bm'])->findOrFail($id);

        // Only allow boardmember to export their own vehicle maintenance
        if (auth()->user()->role === 'boardmember' && $maintenance->vehicle?->bm_id !== auth()->id()) {
            abort(403);
        }

        return Pdf::loadView('maintenances.pdf', compact('maintenance'))
            ->download('maintenance-' . $maintenance->call_of_no . '.pdf');
    }
}
