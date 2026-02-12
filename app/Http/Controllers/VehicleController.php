<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('bm', 'latestFuelSlip')
            ->orderBy('bm_id')
            ->get()
            ->groupBy(function($vehicle) {
                return $vehicle->bm->name ?? 'Unknown';
            });
        
        $boardmembers = \App\Models\User::where('role', 'boardmember')
            ->whereNotNull('office_id')
            ->with('office')
            ->orderBy('name')
            ->get();
        
        return view('vehicles.index', compact('vehicles', 'boardmembers'));
    }

    public function create()
    {
        $boardmembers = null;
        if (auth()->user() && auth()->user()->role === 'admin') {
            // Only show boardmembers that have an office assigned
            $boardmembers = \App\Models\User::where('role', 'boardmember')
                ->whereNotNull('office_id')
                ->with('office')
                ->orderBy('name')
                ->get();
        }

        return view('vehicles.create', compact('boardmembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles',
            'vehicle_name' => 'required|string|max:255',
            'driver' => 'required|string|max:255',
            'boardmember_id' => 'required|exists:users,id',
        ]);

        $boardmember = \App\Models\User::findOrFail($request->input('boardmember_id'));
        
        if (!$boardmember->office_id) {
            return redirect()->back()
                ->withErrors(['boardmember_id' => 'The selected boardmember does not have an office assigned. Please assign an office first.'])
                ->withInput();
        }

        $vehicle = Vehicle::create([
            'bm_id' => $boardmember->id,
            'office_id' => $boardmember->office_id,
            'plate_number' => $request->plate_number,
            'vehicle_name' => $request->vehicle_name,
            'driver' => $request->driver,
            'monthly_fuel_limit' => 100, // Default value set by admin later
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle registered successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles,plate_number,' . $vehicle->id,
            'monthly_fuel_limit' => 'required|numeric',
            'current_km' => 'nullable|integer',
        ]);

        $vehicle->update([
            'plate_number' => $request->plate_number,
            'monthly_fuel_limit' => $request->monthly_fuel_limit,
            'current_km' => $request->current_km ?? $vehicle->current_km,
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
