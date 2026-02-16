<?php

namespace App\Http\Controllers;

use App\Models\FuelSlip;
use App\Models\Vehicle;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FuelSlipController extends Controller
{
    
    private function generateUniqueControlNumber(): string
    {
        $datePart = now()->format('Ymd');

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $randomPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $candidate = "FS-{$datePart}-{$randomPart}";

            if (!FuelSlip::where('control_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        
        return 'FS-' . now()->format('YmdHisv') . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // For admin: fetch boardmembers with their fuel slips
            $boardmembers = \App\Models\User::where('role', 'boardmember')
                ->whereNotNull('office_id')
                ->with(['office', 'fuelSlips' => function($q){ $q->latest(); }])
                ->orderBy('name')
                ->get();
            $fuelSlips = FuelSlip::latest()->get(); // Keep for backward compatibility
        } else {
            // For boardmember: fetch only their fuel slips
            $fuelSlips = FuelSlip::where('user_id', $user->id)->latest()->get();
            $boardmembers = collect(); // Empty collection for boardmember view
        }

        return view('fuel_slips.index', compact('fuelSlips', 'boardmembers'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Provide boardmembers with offices so admin can pick a boardmember then a vehicle
        $boardmembers = \App\Models\User::where('role', 'boardmember')
            ->whereNotNull('office_id')
            ->with(['office.vehicles' => function($q){ $q->orderBy('plate_number'); }])
            ->orderBy('name')
            ->get();

        return view('fuel_slips.create', compact('boardmembers'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $request->validate([
            'boardmember_id' => 'required|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_name' => 'required_without:vehicle_id|string|max:255',
            'plate_number' => 'required_without:vehicle_id|string|max:50',
            'liters' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'km_reading' => 'required|integer|min:0',
            'driver' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $selectedVehicle = null;
        if ($request->filled('vehicle_id')) {
            $selectedVehicle = Vehicle::find($request->vehicle_id);
        }

        FuelSlip::create([
            'user_id' => $request->boardmember_id,
            'vehicle_id' => $selectedVehicle?->id,
            'vehicle_name' => $selectedVehicle?->vehicle_name ?? $request->vehicle_name,
            'plate_number' => $selectedVehicle?->plate_number ?? $request->plate_number,
            'liters' => $request->liters,
            'cost' => $request->cost,
            'km_reading' => $request->km_reading,
            'driver' => $request->driver,
            'control_number' => $this->generateUniqueControlNumber(),
            'date' => $request->date,
        ]);

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'boardmember.dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Fuel slip added successfully.');
    }

    public function exportPDF($id)
    {
        $fuelSlip = FuelSlip::findOrFail($id);

        if (auth()->user()->role === 'boardmember' && $fuelSlip->user_id !== auth()->id()) {
            abort(403);
        }

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('fuel_slips.pdf_template', compact('fuelSlip'))
            ->download('fuel-slip-' . $fuelSlip->control_number . '.pdf');
    }

}
