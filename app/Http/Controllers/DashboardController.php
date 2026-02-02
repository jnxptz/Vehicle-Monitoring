<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\FuelSlip;
use App\Models\Maintenance;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Calculate budget recommendation based on current spending patterns
     */
    private function calculateBudgetRecommendation($userId, $vehicleId, $yearlyBudget)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Get total spent so far this year
        $fuelCost = (float) (FuelSlip::where('user_id', $userId)
            ->whereYear('date', $currentYear)
            ->sum('cost') ?? 0);
            
        $maintenanceCost = (float) (Maintenance::where('vehicle_id', $vehicleId)
            ->whereYear('date', $currentYear)
            ->sum('cost') ?? 0);
            
        $totalUsedSoFar = $fuelCost + $maintenanceCost;
        
        // Calculate average monthly spending
        $averageMonthlySpending = $currentMonth > 0 ? $totalUsedSoFar / $currentMonth : 0;
        
        // Forecast year-end spending
        $projectedYearEndSpending = $averageMonthlySpending * 12;
        
        // Calculate recommendation
        $recommendation = [
            'status' => 'maintain', // maintain, increase, decrease
            'message' => '',
            'projectedYearEnd' => $projectedYearEndSpending,
            'suggestedBudget' => $yearlyBudget,
            'variance' => 0,
        ];
        
        if ($projectedYearEndSpending > 0) {
            $variance = (($projectedYearEndSpending - $yearlyBudget) / $yearlyBudget) * 100;
            $recommendation['variance'] = round($variance, 2);
            
            if ($variance > 20) {
                // Need to increase budget significantly
                $suggestedBudget = ceil($projectedYearEndSpending * 1.1 / 1000) * 1000; // Round up to nearest 1000
                $recommendation['status'] = 'increase';
                $recommendation['suggestedBudget'] = $suggestedBudget;
                $recommendation['message'] = "Based on current spending pace, budget should be increased to ₱" . number_format($suggestedBudget, 2) . " (projected year-end: ₱" . number_format($projectedYearEndSpending, 2) . ")";
            } elseif ($variance < -15) {
                // Budget can be reduced
                $suggestedBudget = max(50000, floor($projectedYearEndSpending * 1.15 / 1000) * 1000); // Round down, minimum 50k
                $recommendation['status'] = 'decrease';
                $recommendation['suggestedBudget'] = $suggestedBudget;
                $savings = $yearlyBudget - $suggestedBudget;
                $recommendation['message'] = "Budget utilization is low. Consider reducing to ₱" . number_format($suggestedBudget, 2) . " (potential savings: ₱" . number_format($savings, 2) . ")";
            } else {
                // Current budget is appropriate
                $recommendation['status'] = 'maintain';
                $recommendation['message'] = "Current budget is appropriate for spending patterns (projected year-end: ₱" . number_format($projectedYearEndSpending, 2) . ")";
            }
        }
        
        return $recommendation;
    }

    
    public function admin(Request $request)
    {
        
        $selectedMonth = (int) $request->input('month', now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }
        $selectedMonthName = \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F');

        $year = now()->year;
        $yearlyBudgetDefault = 100000;

        $boardmembers = User::query()
            ->where('role', 'boardmember')
            ->with('vehicle')
            ->orderBy('name')
            ->get();

        $ids = $boardmembers->pluck('id')->all();

        $totalCostByUserId = FuelSlip::query()
            ->whereIn('user_id', $ids)
            ->whereYear('date', $year)
            ->selectRaw('user_id, SUM(cost) as total_cost')
            ->groupBy('user_id')
            ->pluck('total_cost', 'user_id');

        
        $maintenanceCostsByVehicleId = Maintenance::query()
            ->whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')
            ->groupBy('vehicle_id')
            ->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUserId = FuelSlip::query()
            ->whereIn('user_id', $ids)
            ->whereYear('date', $year)
            ->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')
            ->groupBy('user_id')
            ->pluck('total_liters', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUserId, $maintenanceCostsByVehicleId, $monthlyLitersByUserId, $yearlyBudgetDefault) {
            $vehicle = $bm->vehicle;
            $yearlyBudget = $vehicle ? $yearlyBudgetDefault : 0;

            $fuelCost = (float) ($totalCostByUserId[$bm->id] ?? 0);
            $maintenanceCost = (float) ($vehicle ? ($maintenanceCostsByVehicleId[$vehicle->id] ?? 0) : 0);
            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0
                ? round(($totalUsed / $yearlyBudget) * 100, 2)
                : 0;

            $monthlyLitersUsed = (float) ($monthlyLitersByUserId[$bm->id] ?? 0);
            $monthlyLimit = (float) ($vehicle?->monthly_fuel_limit ?? 0);
            
            $budgetRecommendation = $vehicle ? $this->calculateBudgetRecommendation($bm->id, $vehicle->id, $yearlyBudget) : null;

            return [
                'user' => $bm,
                'vehicle' => $vehicle,
                'yearlyBudget' => $yearlyBudget,
                'totalUsed' => $totalUsed,
                'remainingBudget' => $remainingBudget,
                'budgetUsedPercentage' => $budgetUsedPercentage,
                'monthlyLimit' => $monthlyLimit,
                'monthlyLitersUsed' => $monthlyLitersUsed,
                'budgetRecommendation' => $budgetRecommendation,
            ];
        });

        return view('dashboards.admin', compact('rows', 'selectedMonth', 'selectedMonthName', 'year'));
    }

    
    public function boardmember(Request $request)
    {
        $user = Auth::user();

        
        $vehicle = Vehicle::where('bm_id', $user->id)->first();

        
        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyLitersUsed = 0;
        $maintenanceOverview = collect();
        $budgetRecommendation = null;
        $alerts = [];

        
        $selectedMonth = (int) $request->input('month', now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }
        $selectedMonthName = \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000; // Default yearly budget
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            
            $latestSlipKm = (int) (FuelSlip::where('user_id', $user->id)->max('km_reading') ?? 0);
            $currentKm = max((int) ($vehicle->current_km ?? 0), $latestSlipKm);

           
            $fuelCost = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

       
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0
                ? round(($totalUsed / $yearlyBudget) * 100, 2)
                : 0;

            
            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

         
            if ($monthlyLitersUsed > $monthlyLimit) {
                $alerts[] = "You have exceeded your monthly fuel limit of {$monthlyLimit} liters!";
            }

          
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded! (Fuel + Maintenance costs)";
            }

           
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }

           
            $recentFuel = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->exists();

            if (!$recentFuel) {
                $alerts[] = "No fuel recorded for this month. Remember to submit your fuel slips.";
            }

            $lastPreventiveKm = (int) (Maintenance::where('vehicle_id', $vehicle->id)
                ->where('maintenance_type', 'preventive')
                ->orderByDesc('maintenance_km')
                ->value('maintenance_km') ?? 0);

           
            $nextDueAt = ($lastPreventiveKm > 0) ? ($lastPreventiveKm + 5000) : 5000;

            if ($currentKm >= $nextDueAt) {
                $alerts[] = "Preventive Maintenance System: Preventive maintenance is due every 5,000 km. Last preventive KM: {$lastPreventiveKm} km. Next due at: {$nextDueAt} km. Current KM: {$currentKm} km.";
            }

            
            $maintenanceOverview = Maintenance::where('vehicle_id', $vehicle->id)
                ->latest('date')
                ->take(5)
                ->get();
            
            $budgetRecommendation = $this->calculateBudgetRecommendation($user->id, $vehicle->id, $yearlyBudget);
        }

        return view('dashboards.boardmember', compact(
            'vehicle',
            'yearlyBudget',
            'remainingBudget',
            'budgetUsedPercentage',
            'monthlyLimit',
            'monthlyLitersUsed',
            'maintenanceOverview',
            'budgetRecommendation',
            'alerts',
            'selectedMonth',
            'selectedMonthName'
        ));
    }

    public function exportPdf(Request $request)
    {
        
        $user = Auth::user();
        $vehicle = Vehicle::where('bm_id', $user->id)->first();

        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyLitersUsed = 0;
        $alerts = [];

        $selectedMonth = (int) $request->input('month', now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }
        $selectedMonthName = \Carbon\Carbon::createFromDate(null, $selectedMonth, 1)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0
                ? round(($totalUsed / $yearlyBudget) * 100, 2)
                : 0;

            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

            if ($monthlyLitersUsed > $monthlyLimit) {
                $alerts[] = "You have exceeded your monthly fuel limit of {$monthlyLimit} liters!";
            }
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded! (Fuel + Maintenance costs)";
            }
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }

            $recentFuel = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->exists();
            if (!$recentFuel) {
                $alerts[] = "No fuel recorded for {$selectedMonthName}.";
            }
        }

        $pdf = Pdf::loadView('dashboards.boardmember_pdf', compact(
            'vehicle',
            'yearlyBudget',
            'remainingBudget',
            'budgetUsedPercentage',
            'monthlyLimit',
            'monthlyLitersUsed',
            'alerts',
            'selectedMonth',
            'selectedMonthName'
        ));

        $filename = 'dashboard-' . now()->format('Y-m-d') . '-' . strtolower($selectedMonthName) . '.pdf';
        return $pdf->download($filename);
    }
}
