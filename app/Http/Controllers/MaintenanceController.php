<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    
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

        if ($user && $user->role === 'admin') {
            // For admin: fetch boardmembers with their maintenances
            $boardmembers = User::where('role', 'boardmember')
                ->whereNotNull('office_id')
                ->with(['office', 'vehicles' => function($q){ 
                    $q->with(['maintenances' => function($mq){ $mq->latest(); }]); 
                }])
                ->orderBy('name')
                ->get();
            $maintenances = Maintenance::with('vehicle')->latest()->get(); // Keep for backward compatibility
        } else {
            // For boardmember: fetch only their vehicles' maintenances
            $maintenances = Maintenance::with('vehicle')
                ->whereHas('vehicle', function ($q) use ($user) {
                    $q->where('bm_id', $user->id);
                })
                ->latest()
                ->get();
            $boardmembers = collect(); // Empty collection for boardmember view
        }
        
        $vehicles = Vehicle::orderBy('plate_number')->get();
        
        return view('maintenances.index', compact('maintenances', 'vehicles', 'boardmembers'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        if ($vehicles->isEmpty()) {
            return redirect()->route('maintenances.index')
                ->with('error', 'Add at least one vehicle before creating a maintenance record.');
        }
        return view('maintenances.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type' => 'required|in:preventive,repair',
            'maintenance_km' => 'required|integer|min:0',
            'operation' => 'required|string|max:65535',
            'cost' => 'required|numeric|min:0',
            'conduct' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);

        $data = array_merge($validated, [
            'call_of_no' => $this->generateUniqueCallOfNo(),
            'photo' => null,
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('maintenance_photos', 'public');
        }

        try {
            Maintenance::create($data);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Could not save maintenance. Please try again.');
        }

        return redirect()->route('maintenances.index')->with('success', 'Maintenance recorded.');
    }

    public function exportPDF($id)
    {
        $maintenance = Maintenance::with(['vehicle', 'vehicle.bm'])->findOrFail($id);

        
        if (auth()->user()->role === 'boardmember' && $maintenance->vehicle?->bm_id !== auth()->id()) {
            abort(403);
        }

        return Pdf::loadView('maintenances.pdf', compact('maintenance'))
            ->download('maintenance-' . $maintenance->call_of_no . '.pdf');
    }
}
