<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('bm')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:vehicles',
            'monthly_fuel_limit' => 'required|numeric',
        ]);

        $vehicle = Vehicle::create([
            'bm_id' => Auth::id(), 
            'plate_number' => $request->plate_number,
            'monthly_fuel_limit' => $request->monthly_fuel_limit,
        ]);

        return redirect()->route('boardmember.dashboard')
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
