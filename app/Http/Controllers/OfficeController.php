<?php

namespace App\Http\Controllers;

use App\Models\BM;
use App\Models\Maintenance;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display all offices
     */
    public function index()
    {
        $offices = Office::with(['vehicles.bm', 'users'])
            ->withCount('vehicles', 'users')
            ->orderBy('name')
            ->get();
        return view('offices.index', compact('offices'));
    }

    /**
     * Show the create office form
     */
    public function create()
    {
        return view('offices.create');
    }

    /**
     * Store a new office
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices',
            'address' => 'nullable|string|max:500',
        ]);

        Office::create($request->only(['name', 'address']));

        return redirect()->route('offices.index')->with('success', 'Office created successfully.');
    }

    /**
     * Show the edit office form
     */
    public function edit(Office $office)
    {
        return view('offices.edit', compact('office'));
    }

    /**
     * Update an office
     */
    public function update(Request $request, Office $office)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $office->id,
            'address' => 'nullable|string|max:500',
        ]);

        $office->update($request->only(['name', 'address']));

        return redirect()->route('offices.index')->with('success', 'Office updated successfully.');
    }

    /**
     * Delete an office
     */
    public function destroy(Office $office)
    {
        // Prevent deletion if office has vehicles or boardmembers
        if ($office->vehicles()->count() > 0 || $office->users()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete office that has vehicles or boardmembers assigned.']);
        }

        $office->delete();

        return redirect()->route('offices.index')->with('success', 'Office deleted successfully.');
    }

    /**
     * Show the page to assign boardmembers to offices
     */
    public function manageBoardmembers()
    {
        $offices = Office::all();
        $boardmembers = User::where('role', 'boardmember')->orderBy('name')->get();

        return view('offices.manage-boardmembers', compact('offices', 'boardmembers'));
    }

    /**
     * Update a boardmember's office assignment
     */
    public function assignBoardmember(Request $request, User $user)
    {
        $request->validate([
            'office_id' => 'required|exists:offices,id',
        ]);

        $user->update(['office_id' => $request->office_id]);

        return redirect()->back()->with('success', "{$user->name} has been assigned to the office successfully.");
    }

    /**
     * Show the edit boardmember form
     */
    public function editBoardmember(User $user)
    {
        $offices = Office::all();
        $bm = $user->bm;
        return view('boardmembers.edit', compact('user', 'offices', 'bm'));
    }

    /**
     * Update a boardmember
     */
    public function updateBoardmember(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'office_id' => 'nullable|exists:offices,id',
            'yearly_budget' => 'nullable|numeric|min:0',
        ]);

        // Update user information
        $user->update($request->only(['name', 'email', 'office_id']));

        // Update or create BM record with budget
        if ($request->has('yearly_budget') && $request->yearly_budget !== null) {
            $bm = $user->bm ?: new BM([
                'user_id' => $user->id,
                'name' => $user->name
            ]);
            $bm->yearly_budget = $request->yearly_budget;
            $bm->save();
        }

        return redirect()->route('offices.manage-boardmembers')->with('success', "{$user->name} has been updated successfully.");
    }

    /**
     * Delete a boardmember
     */
    public function destroyBoardmember(User $user)
    {
        // Get all vehicles before unassigning them
        $vehicles = $user->vehicles()->get();

        // Delete all fuel slips associated with this boardmember
        $fuelSlips = $user->fuelSlips()->get();
        foreach ($fuelSlips as $fuelSlip) {
            $fuelSlip->delete();
        }

        // Delete all maintenance records associated with this boardmember's vehicles
        foreach ($vehicles as $vehicle) {
            $maintenances = Maintenance::where('vehicle_id', $vehicle->id)->get();
            foreach ($maintenances as $maintenance) {
                $maintenance->delete();
            }
        }

        // Unassign all vehicles from this boardmember (make them available for others)
        foreach ($vehicles as $vehicle) {
            $vehicle->update(['bm_id' => null]);
        }

        // Delete BM record if it exists
        if ($user->bm) {
            $user->bm->delete();
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('offices.manage-boardmembers')->with('success', "{$userName} has been deleted successfully. All vehicles have been unassigned and are now available for other boardmembers. Fuel slips, maintenance records, and budget data have been removed.");
    }
}
