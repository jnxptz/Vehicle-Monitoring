<?php

namespace App\Http\Controllers;

use App\Models\FuelSlip;
use App\Models\Vehicle;
use App\Models\Office;
use App\Models\User;
use App\Models\BM;
use App\Mail\BudgetAdjustmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
            'yearly_budget' => 'nullable|numeric',
        ]);

        // Update user information
        $user->update($request->only(['name', 'email', 'office_id']));

        // Update or create BM record with budget
        $topUpAmount = $request->input('yearly_budget');
        
        // Always handle budget (including empty/zero values)
        $bm = $user->bm ?: new BM([
            'user_id' => $user->id,
            'name' => $user->name,
        ]);
        
        $oldBudget = $bm->yearly_budget ?? 0;
        $adjustmentAmount = $request->input('yearly_budget');
        
        if ($adjustmentAmount !== null && $adjustmentAmount !== '') {
            $adjustmentAmount = floatval($adjustmentAmount);
            
            if ($adjustmentAmount != 0) {
                // Calculate new budget
                $newBudget = $oldBudget + $adjustmentAmount;
                
                // Prevent negative budget
                if ($newBudget < 0) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Cannot decrease budget by ₱" . number_format(abs($adjustmentAmount), 2) . ". Current budget is only ₱" . number_format($oldBudget, 2));
                }
                
                $bm->yearly_budget = $newBudget;
                $bm->save();
                
                $type = $adjustmentAmount > 0 ? 'increase' : 'decrease';
                
                \Log::info('Budget adjusted for user ' . $user->id . ': ' . ($adjustmentAmount > 0 ? 'Added' : 'Subtracted') . ' ₱' . abs($adjustmentAmount) . ', New total: ₱' . $newBudget);
                
                // Send email notification to the user
                try {
                    \Mail::to($user->email)->send(new \App\Mail\BudgetAdjustmentMail(
                        $user,
                        $adjustmentAmount,
                        $oldBudget,
                        $newBudget,
                        $type
                    ));
                    \Log::info('Budget adjustment email sent to: ' . $user->email);
                    $emailStatus = 'Email notification sent.';
                } catch (\Exception $e) {
                    \Log::error('Failed to send budget adjustment email: ' . $e->getMessage());
                    $emailStatus = 'Email notification failed.';
                }
                
                return redirect()->route('offices.manage-boardmembers')
                    ->with('success', "{$user->name} has been updated successfully. Budget " . ($adjustmentAmount > 0 ? "increased" : "decreased") . " by ₱" . number_format(abs($adjustmentAmount), 2) . ". " . $emailStatus);
            }
        }
        
        $bm->save();

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

    /**
     * Register a new boardmember
     */
    public function registerBoardmember(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'office_id' => 'nullable|exists:offices,id',
            'yearly_budget' => 'nullable|numeric|min:0',
        ]);

        try {
            // Create the new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'boardmember',
                'office_id' => $request->office_id,
                'email_verified_at' => now(), // Auto-verify email for admin-created accounts
            ]);

            // Create BM record with budget if provided
            if ($request->filled('yearly_budget')) {
                BM::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'yearly_budget' => $request->yearly_budget,
                ]);
            } else {
                // Create BM record with default budget
                BM::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'yearly_budget' => 100000, // Default budget
                ]);
            }

            return redirect()->route('offices.manage-boardmembers')->with('success', "Boardmember '{$user->name}' has been registered successfully! They can now log in with their credentials.");

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Boardmember registration failed: ' . $e->getMessage());
            
            return redirect()->route('offices.manage-boardmembers')
                ->with('error', 'Registration failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}
