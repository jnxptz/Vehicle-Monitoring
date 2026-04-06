<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        // Check if show_all is requested
        $showAll = request('show_all') || !request()->has('office');
        
        // Get filtered boardmembers for display
        $query = \App\Models\User::where('role', 'boardmember')
            ->with('office', 'vehicles')
            ->orderBy('name');

        // Only filter by office if not showing all and office is selected
        if (!$showAll && request('office')) {
            $query->where('office_id', request('office'));
        } elseif (!$showAll) {
            // When not showing all and no office selected, only show boardmembers with offices
            $query->whereNotNull('office_id');
        }

        $boardmembers = $query->get();

        // Get all boardmembers for the registration modal (unfiltered)
        $allBoardmembers = \App\Models\User::where('role', 'boardmember')
            ->whereNotNull('office_id')
            ->with('office')
            ->orderBy('name')
            ->get();

        $offices = \App\Models\Office::orderBy('name')->get();
        
        return view('vehicles.index', compact('boardmembers', 'allBoardmembers', 'offices'));
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
        // Dynamic validation based on user role
        $rules = [
            'plate_number' => 'required|unique:vehicles',
            'vehicle_name' => 'required|string|max:255',
            'driver' => 'required|string|max:255',
        ];

        // Only require boardmember_id if user is admin and it's provided
        if (auth()->user()->role === 'admin') {
            $rules['boardmember_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        // Handle boardmember assignment
        $boardmemberId = null;
        $officeId = null;

        if (auth()->user()->role === 'admin' && $request->filled('boardmember_id')) {
            $boardmember = \App\Models\User::findOrFail($request->input('boardmember_id'));
            
            if (!$boardmember->office_id) {
                return redirect()->back()
                    ->withErrors(['boardmember_id' => 'The selected boardmember does not have an office assigned. Please assign an office first.'])
                    ->withInput();
            }

            $boardmemberId = $boardmember->id;
            $officeId = $boardmember->office_id;
        }

        $vehicle = Vehicle::create([
            'bm_id' => $boardmemberId,
            'office_id' => $officeId,
            'plate_number' => $request->plate_number,
            'vehicle_name' => $request->vehicle_name,
            'driver' => $request->driver,
            'monthly_fuel_limit' => 100, // Default value set by admin later
        ]);

        $message = $boardmemberId 
            ? 'Vehicle registered successfully and assigned to ' . $boardmember->name . '.'
            : 'Vehicle registered successfully. You can assign it to a boardmember later.';

        return redirect()->route('vehicles.index')
            ->with('success', $message);
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

    public function getAllVehicles()
    {
        $vehicles = \App\Models\Vehicle::with(['bm.office'])->get();
        
        return response()->json($vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'make' => $vehicle->vehicle_name ?? 'Unknown',
                'model' => $vehicle->driver ?? 'N/A',
                'plate_number' => $vehicle->plate_number,
                'bm_id' => $vehicle->bm_id,
                'boardmember' => $vehicle->bm ? [
                    'id' => $vehicle->bm->id,
                    'name' => $vehicle->bm->name,
                    'office' => $vehicle->bm->office ? [
                        'id' => $vehicle->bm->office->id,
                        'name' => $vehicle->bm->office->name
                    ] : null
                ] : null
            ];
        }));
    }

    public function assignVehicle(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'boardmember_id' => 'required|exists:users,id',
        ]);

        $boardmember = \App\Models\User::findOrFail($request->input('boardmember_id'));
        
        if (!$boardmember->office_id) {
            return redirect()->back()
                ->withErrors(['boardmember_id' => 'The selected boardmember does not have an office assigned. Please assign an office first.'])
                ->withInput();
        }

        // Update vehicle assignment
        $vehicle->update([
            'bm_id' => $boardmember->id,
            'office_id' => $boardmember->office_id,
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', "Vehicle '{$vehicle->vehicle_name}' has been successfully assigned to {$boardmember->name}.");
    }
}
