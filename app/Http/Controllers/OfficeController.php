<?php

namespace App\Http\Controllers;

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
        $offices = Office::withCount('vehicles', 'users')->orderBy('name')->get();
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
}
