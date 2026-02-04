<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\FuelSlip;
use App\Models\Maintenance;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Calculate budget recommendation based on spending patterns
     */
    private function calculateBudgetRecommendation($userId, $vehicleId, $yearlyBudget)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $fuelCost = (float) FuelSlip::where('user_id', $userId)
            ->whereYear('date', $currentYear)
            ->sum('cost');

        $maintenanceCost = (float) Maintenance::where('vehicle_id', $vehicleId)
            ->whereYear('date', $currentYear)
            ->sum('cost');

        $totalUsed = $fuelCost + $maintenanceCost;

        // Average monthly spending
        $avgMonthly = $currentMonth > 0 ? $totalUsed / $currentMonth : 0;

        // Projected year-end with 10% buffer
        $projectedYearEnd = $avgMonthly * 12 * 1.10;

        // Hard caps to prevent unrealistic recommendations
        $maxBudget = $yearlyBudget * 1.15;
        $minBudget = $yearlyBudget * 0.85;
        $suggestedBudget = min(max($projectedYearEnd, $minBudget), $maxBudget);

        // Determine status
        if ($suggestedBudget > $yearlyBudget * 1.05) {
            $status = 'increase';
        } elseif ($suggestedBudget < $yearlyBudget * 0.95) {
            $status = 'decrease';
        } else {
            $status = 'maintain';
        }

        return [
            'status' => $status,
            'suggestedBudget' => round($suggestedBudget, 2),
            'projectedYearEnd' => round($projectedYearEnd, 2),
        ];
    }

    /**
     * Admin dashboard
     */
    public function admin(Request $request)
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
        $selectedMonthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');
        $year = now()->year;
        $yearlyBudgetDefault = 100000;

        $boardmembers = User::where('role', 'boardmember')->with('vehicle')->orderBy('name')->get();
        $ids = $boardmembers->pluck('id');

        $totalCostByUser = FuelSlip::whereIn('user_id', $ids)->whereYear('date', $year)
            ->selectRaw('user_id, SUM(cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $maintenanceByVehicle = Maintenance::whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')->groupBy('vehicle_id')->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')->groupBy('user_id')->pluck('total_liters', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUser, $maintenanceByVehicle, $monthlyLitersByUser, $yearlyBudgetDefault) {
            $vehicle = $bm->vehicle;
            $yearlyBudget = $vehicle ? $yearlyBudgetDefault : 0;

            $fuelCost = (float) ($totalCostByUser[$bm->id] ?? 0);
            $maintenanceCost = $vehicle ? (float) ($maintenanceByVehicle[$vehicle->id] ?? 0) : 0;
            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = (float) ($monthlyLitersByUser[$bm->id] ?? 0);
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

    /**
     * Boardmember dashboard
     */
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
        $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
        $selectedMonthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $latestKm = (int) FuelSlip::where('user_id', $user->id)->max('km_reading');
            $currentKm = max((int) ($vehicle->current_km ?? 0), $latestKm);

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('cost');
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)->whereYear('date', now()->year)->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

            // Alerts
            if ($monthlyLitersUsed > $monthlyLimit) {
                $alerts[] = "You have exceeded your monthly fuel limit of {$monthlyLimit} liters!";
            }
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded!";
            }
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }
            $recentFuel = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)->whereMonth('date', $selectedMonth)->exists();
            if (!$recentFuel) {
                $alerts[] = "No fuel recorded for {$selectedMonthName}.";
            }

            $lastPreventiveKm = (int) Maintenance::where('vehicle_id', $vehicle->id)
                ->where('maintenance_type', 'preventive')
                ->orderByDesc('maintenance_km')->value('maintenance_km');

            $nextDueKm = ($lastPreventiveKm > 0) ? ($lastPreventiveKm + 5000) : 5000;
            if ($currentKm >= $nextDueKm) {
                $alerts[] = "Preventive maintenance due: last at {$lastPreventiveKm} km, next at {$nextDueKm} km, current: {$currentKm} km.";
            }

            $maintenanceOverview = Maintenance::where('vehicle_id', $vehicle->id)->latest('date')->take(5)->get();
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

    /**
     * Export boardmember dashboard PDF
     */
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
        $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
        $selectedMonthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('cost');
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)->whereYear('date', now()->year)->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

            if ($monthlyLitersUsed > $monthlyLimit) {
                $alerts[] = "You have exceeded your monthly fuel limit of {$monthlyLimit} liters!";
            }
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded!";
            }
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }
            $recentFuel = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)->whereMonth('date', $selectedMonth)->exists();
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

    /**
     * Export boardmember yearly PDF (January to December)
     */
    public function exportYearlyPdf()
    {
        $user = Auth::user();
        $vehicle = Vehicle::where('bm_id', $user->id)->first();

        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyData = [];
        $alerts = [];

        if ($vehicle) {
            $yearlyBudget = 100000;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('cost');
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)->whereYear('date', now()->year)->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            // Get monthly data for all 12 months
            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::createFromDate(null, $month, 1)->format('F');
                $monthlyLiters = FuelSlip::where('user_id', $user->id)
                    ->whereYear('date', now()->year)
                    ->whereMonth('date', $month)
                    ->sum('liters');
                $monthlyCost = FuelSlip::where('user_id', $user->id)
                    ->whereYear('date', now()->year)
                    ->whereMonth('date', $month)
                    ->sum('cost');

                $monthlyData[] = [
                    'month' => $monthName,
                    'liters' => $monthlyLiters,
                    'cost' => $monthlyCost,
                ];
            }

            // Generate year-to-date alerts
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded!";
            }
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }
        }

        $pdf = Pdf::loadView('dashboards.boardmember_yearly_pdf', compact(
            'vehicle',
            'yearlyBudget',
            'remainingBudget',
            'budgetUsedPercentage',
            'monthlyLimit',
            'monthlyData',
            'alerts'
        ));

        $filename = 'dashboard-yearly-' . now()->year . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export admin yearly PDF (fleet-wide January to December)
     */
    public function exportAdminYearlyPdf()
    {
        $year = now()->year;
        $monthlyData = [];
        $totalLiters = 0;
        $totalCost = 0;

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate(null, $month, 1)->format('F');
            $monthlyLiters = FuelSlip::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('liters');
            $monthlyCost = FuelSlip::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('cost');

            $monthlyData[] = [
                'month' => $monthName,
                'liters' => $monthlyLiters,
                'cost' => $monthlyCost,
            ];

            $totalLiters += $monthlyLiters;
            $totalCost += $monthlyCost;
        }

        // Find highest consumption month across fleet
        $sorted = collect($monthlyData)->sortByDesc('liters');
        $highest = $sorted->first() ?? null;

        // Optionally include top vehicles by liters (top 5)
        $vehicles = Vehicle::all();
        $topVehicles = collect();
        foreach ($vehicles as $v) {
            $vLiters = FuelSlip::where('vehicle_id', $v->id)->whereYear('date', $year)->sum('liters');
            if ($vLiters > 0) {
                $topVehicles->push([ 'vehicle' => $v, 'liters' => $vLiters ]);
            }
        }
        $topVehicles = $topVehicles->sortByDesc('liters')->take(5)->values();

        $pdf = Pdf::loadView('dashboards.admin_yearly_pdf', compact(
            'monthlyData', 'totalLiters', 'totalCost', 'highest', 'topVehicles', 'year'
        ));

        $filename = 'admin-dashboard-yearly-' . $year . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
