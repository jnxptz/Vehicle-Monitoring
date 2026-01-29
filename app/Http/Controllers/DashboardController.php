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
    // Admin dashboard (overview of all boardmembers)
    public function admin(Request $request)
    {
        // Selected month (1-12), default to current month
        $selectedMonth = (int) $request->input('month', now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }
        $selectedMonthName = now()->setMonth($selectedMonth)->format('F');

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

        // Get maintenance costs by vehicle_id (linked to boardmember via vehicle.bm_id)
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

            return [
                'user' => $bm,
                'vehicle' => $vehicle,
                'yearlyBudget' => $yearlyBudget,
                'totalUsed' => $totalUsed,
                'remainingBudget' => $remainingBudget,
                'budgetUsedPercentage' => $budgetUsedPercentage,
                'monthlyLimit' => $monthlyLimit,
                'monthlyLitersUsed' => $monthlyLitersUsed,
            ];
        });

        return view('dashboards.admin', compact('rows', 'selectedMonth', 'selectedMonthName', 'year'));
    }

    // Boardmember dashboard
    public function boardmember(Request $request)
    {
        $user = Auth::user();

        // Get user's vehicle
        $vehicle = Vehicle::where('bm_id', $user->id)->first();

        // Default values
        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyLitersUsed = 0;
        $maintenanceOverview = collect();
        $alerts = [];

        // Selected month (1-12), default to current month
        $selectedMonth = (int) $request->input('month', now()->month);
        if ($selectedMonth < 1 || $selectedMonth > 12) {
            $selectedMonth = now()->month;
        }
        $selectedMonthName = now()->setMonth($selectedMonth)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000; // Default yearly budget
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            // Current KM (use latest fuel slip KM if it's higher than stored vehicle KM)
            $latestSlipKm = (int) (FuelSlip::where('user_id', $user->id)->max('km_reading') ?? 0);
            $currentKm = max((int) ($vehicle->current_km ?? 0), $latestSlipKm);

            // Fuel used this year (by this boardmember)
            // We use user_id so the dashboard always reflects what the user encoded,
            // even if a slip was entered with a wrong plate number.
            $fuelCost = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            // Maintenance costs this year (for this vehicle)
            $maintenanceCost = Maintenance::where('vehicle_id', $vehicle->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0
                ? round(($totalUsed / $yearlyBudget) * 100, 2)
                : 0;

            // Fuel used this month (by this boardmember)
            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

            // -----------------------
            // BUSINESS RULES / ALERTS
            // -----------------------

            // 1️⃣ Exceed monthly fuel limit
            if ($monthlyLitersUsed > $monthlyLimit) {
                $alerts[] = "You have exceeded your monthly fuel limit of {$monthlyLimit} liters!";
            }

            // 2️⃣ Exceed yearly budget
            if ($remainingBudget < 0) {
                $alerts[] = "Your yearly budget of ₱" . number_format($yearlyBudget, 2) . " has been exceeded! (Fuel + Maintenance costs)";
            }

            // 3️⃣ Low remaining budget warning (<20%)
            if ($remainingBudget > 0 && $budgetUsedPercentage >= 80) {
                $alerts[] = "Warning: You have used {$budgetUsedPercentage}% of your yearly budget.";
            }

            // 4️⃣ No recent fuel slip added this month
            $recentFuel = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->exists();

            if (!$recentFuel) {
                $alerts[] = "No fuel recorded for this month. Remember to submit your fuel slips.";
            }

            // 5️⃣ Preventive Maintenance System (every 5,000 KM)
            // Reset is based on the last recorded PREVENTIVE maintenance KM.
            $lastPreventiveKm = (int) (Maintenance::where('vehicle_id', $vehicle->id)
                ->where('maintenance_type', 'preventive')
                ->orderByDesc('maintenance_km')
                ->value('maintenance_km') ?? 0);

            // If no preventive maintenance recorded yet, treat it as starting from 0 km.
            $nextDueAt = ($lastPreventiveKm > 0) ? ($lastPreventiveKm + 5000) : 5000;

            if ($currentKm >= $nextDueAt) {
                $alerts[] = "Preventive Maintenance System: Preventive maintenance is due every 5,000 km. Last preventive KM: {$lastPreventiveKm} km. Next due at: {$nextDueAt} km. Current KM: {$currentKm} km.";
            }

            // Maintenance overview (latest 5 records for this vehicle)
            $maintenanceOverview = Maintenance::where('vehicle_id', $vehicle->id)
                ->latest('date')
                ->take(5)
                ->get();
        }

        return view('dashboards.boardmember', compact(
            'vehicle',
            'yearlyBudget',
            'remainingBudget',
            'budgetUsedPercentage',
            'monthlyLimit',
            'monthlyLitersUsed',
            'maintenanceOverview',
            'alerts',
            'selectedMonth',
            'selectedMonthName'
        ));
    }

    public function exportPdf(Request $request)
    {
        // Reuse the same calculations as the dashboard view
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
        $selectedMonthName = now()->setMonth($selectedMonth)->format('F');

        if ($vehicle) {
            $yearlyBudget = 100000;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->sum('cost');

            // Maintenance costs this year (for this vehicle)
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
