<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\FuelSlip;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Office;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private const YEARLY_BUDGET_DEFAULT = 100000;

    /**
     * Calculate budget recommendation based on usage
     */
    private function calculateBudgetRecommendation($userId, $vehicleId, $yearlyBudget)
    {
        $year = now()->year;

        $fuelCost = FuelSlip::where('user_id', $userId)
            ->whereYear('date', $year)
            ->sum('cost');

        $maintenanceCost = Maintenance::where('vehicle_id', $vehicleId)
            ->whereYear('date', $year)
            ->sum('cost');

        $totalUsed = $fuelCost + $maintenanceCost;
        $remaining = $yearlyBudget - $totalUsed;

        if ($yearlyBudget <= 0) {
            return [
                'status' => 'maintain',
                'suggestedBudget' => 0,
                'remaining' => 0,
                'remainingPercent' => 0
            ];
        }

        $usedPercent = ($totalUsed / $yearlyBudget) * 100;
        $remainingPercent = ($remaining / $yearlyBudget) * 100;

        if ($usedPercent >= 90) {
            $status = 'increase';
            $suggestedBudget = $yearlyBudget * 1.05;
        } elseif ($remainingPercent >= 40) {
            $status = 'decrease';
            $suggestedBudget = $yearlyBudget * 0.85;
        } else {
            $status = 'maintain';
            $suggestedBudget = $yearlyBudget;
        }

        return [
            'status' => $status,
            'suggestedBudget' => round($suggestedBudget, 2),
            'remaining' => round($remaining, 2),
            'remainingPercent' => round($remainingPercent, 2)
        ];
    }

    /**
     * Admin dashboard showing all boardmembers and vehicles
     */
    public function admin(Request $request)
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
        $selectedMonthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');
        $year = now()->year;
        
        $selectedOffice = $request->input('office', null);
        $offices = Office::orderBy('name')->get();

        $query = User::where('role', 'boardmember')
            ->with(['vehicles', 'office.vehicles']);
        
        if ($selectedOffice) {
            $query->where('office_id', $selectedOffice);
        }
        
        $boardmembers = $query->orderBy('name')->get();
        $ids = $boardmembers->pluck('id');

        $totalCostByUser = FuelSlip::whereIn('user_id', $ids)->whereYear('date', $year)
            ->selectRaw('user_id, SUM(cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $maintenanceByVehicle = Maintenance::whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')->groupBy('vehicle_id')->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')->groupBy('user_id')->pluck('total_liters', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUser, $maintenanceByVehicle, $monthlyLitersByUser) {
            // Get all vehicles for this board member
            $vehicles = $bm->vehicles()->get();
            
            $fuelCost = (float) ($totalCostByUser[$bm->id] ?? 0);
            $yearlyBudget = self::YEARLY_BUDGET_DEFAULT;
            
            // Get fuel slips per vehicle for this user
            $year = now()->year;
            $fuelSlipsByVehicle = FuelSlip::where('user_id', $bm->id)
                ->whereYear('date', $year)
                ->selectRaw('vehicle_id, SUM(cost) as total_cost, SUM(liters) as total_liters')
                ->groupBy('vehicle_id')
                ->pluck('total_cost', 'vehicle_id');
            
            // Calculate vehicle details
            $vehiclesDetails = $vehicles->map(function($vehicle) use ($maintenanceByVehicle, $fuelSlipsByVehicle) {
                $maintenanceCost = (float) ($maintenanceByVehicle[$vehicle->id] ?? 0);
                $fuelSlipCost = (float) ($fuelSlipsByVehicle[$vehicle->id] ?? 0);
                return [
                    'vehicle' => $vehicle,
                    'maintenanceCost' => $maintenanceCost,
                    'fuelSlipCost' => $fuelSlipCost,
                ];
            })->toArray();
            
            // Calculate totals
            $maintenanceCostTotal = collect($vehiclesDetails)->sum('maintenanceCost');
            $totalUsed = $fuelCost + $maintenanceCostTotal;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = (float) ($monthlyLitersByUser[$bm->id] ?? 0);

            return [
                'user' => $bm,
                'vehicles' => $vehiclesDetails,
                'yearlyBudget' => $yearlyBudget,
                'totalUsed' => $totalUsed,
                'remainingBudget' => $remainingBudget,
                'budgetUsedPercentage' => $budgetUsedPercentage,
                'monthlyLitersUsed' => $monthlyLitersUsed,
                'fuelCost' => $fuelCost,
                'maintenanceCostTotal' => $maintenanceCostTotal,
            ];
        });

        return view('dashboards.admin', compact('rows', 'selectedMonth', 'selectedMonthName', 'year', 'offices', 'selectedOffice'));
    }

    /**
     * Boardmember dashboard showing their vehicle and budget
     */
    public function boardmember(Request $request)
    {
        $user = Auth::user();
        // fetch all vehicles for this boardmember so the view can list them
        $vehicles = $user->vehicles()->get();
        // prefer user's direct vehicle, otherwise use the first vehicle from their office
        $vehicle = $user->vehicles()->first() ?? ($user->office?->vehicles()->first());

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
            $yearlyBudget = self::YEARLY_BUDGET_DEFAULT;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            // Use latest km for this specific vehicle (not user's across all vehicles)
            $latestKm = (int) FuelSlip::where('vehicle_id', $vehicle->id)->max('km_reading');
            $currentKm = max((int) ($vehicle->current_km ?? 0), $latestKm);

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('cost');
            // Sum maintenance costs across all vehicles for this boardmember so the
            // boardmember view reflects total spend (matches admin view behavior).
            $vehicleIds = $vehicles->pluck('id')->toArray();
            $maintenanceCost = Maintenance::whereIn('vehicle_id', $vehicleIds)->whereYear('date', now()->year)->sum('cost');

            $totalUsed = $fuelCost + $maintenanceCost;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = FuelSlip::where('user_id', $user->id)
                ->whereYear('date', now()->year)
                ->whereMonth('date', $selectedMonth)
                ->sum('liters');

            // Generate alerts
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

            // Treat the latest maintenance (any type) as the last preventive checkpoint.
            // If the latest maintenance record lacks `maintenance_km`, fall back to the highest known maintenance_km.
            $lastMaintenance = Maintenance::where('vehicle_id', $vehicle->id)
                ->latest('date')
                ->first();

            $lastMaintenanceKm = $lastMaintenance?->maintenance_km ? (int) $lastMaintenance->maintenance_km : null;

            if (is_null($lastMaintenanceKm)) {
                $maxKm = Maintenance::where('vehicle_id', $vehicle->id)
                    ->whereNotNull('maintenance_km')
                    ->max('maintenance_km');
                $lastMaintenanceKm = $maxKm ? (int) $maxKm : 0;
            }

            $lastMaintenanceType = $lastMaintenance?->maintenance_type ?? 'N/A';

            $nextDueKm = ($lastMaintenanceKm > 0) ? ($lastMaintenanceKm + 5000) : 5000;
            if ($currentKm >= $nextDueKm) {
                $alerts[] = "Preventive maintenance due: last ({$lastMaintenanceType}) at {$lastMaintenanceKm} km, next at {$nextDueKm} km, current: {$currentKm} km.";
            }

            $maintenanceOverview = Maintenance::where('vehicle_id', $vehicle->id)->latest('date')->take(5)->get();
            $budgetRecommendation = $this->calculateBudgetRecommendation($user->id, $vehicle->id, $yearlyBudget);
        }

        return view('dashboards.boardmember', compact(
            'vehicle',
            'vehicles',
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
     * Export boardmember dashboard as PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        // prefer user's direct vehicle, otherwise use first vehicle from their office
        $vehicle = $user->vehicles()->first() ?? ($user->office?->vehicles()->first());

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
            $yearlyBudget = self::YEARLY_BUDGET_DEFAULT;
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

            // Generate alerts
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
     * Export boardmember yearly PDF
     */
    public function exportYearlyPdf()
    {
        $user = Auth::user();
        // prefer user's direct vehicle, otherwise use first vehicle from their office
        $vehicle = $user->vehicle ?? ($user->office?->vehicles()->first());

        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyData = [];
        $alerts = [];

        if ($vehicle) {
            $yearlyBudget = self::YEARLY_BUDGET_DEFAULT;
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

        
        $highest = collect($monthlyData)->sortByDesc('liters')->first();

        
        $vehicles = Vehicle::with('bm')->get();
        $topVehicles = $vehicles->map(function ($v) use ($year) {
            $vLiters = FuelSlip::where('vehicle_id', $v->id)
                ->whereYear('date', $year)
                ->sum('liters');
            return ['vehicle' => $v, 'liters' => $vLiters];
        })->filter(fn($v) => $v['liters'] > 0)
          ->sortByDesc('liters')
          ->take(5)
          ->values();

        
        $boardmembers = User::where('role', 'boardmember')
            ->with(['vehicles', 'office.vehicles'])
            ->orderBy('name')
            ->get();

        $boardmembersData = $boardmembers->map(function ($bm) use ($year) {
            // prefer user's direct vehicle, otherwise fall back to their office's first vehicle
            $vehicle = $bm->vehicle ?? ($bm->office?->vehicles->first() ?? null);
            $yearlyBudget = $vehicle ? self::YEARLY_BUDGET_DEFAULT : 0;

            $fuelCost = FuelSlip::where('user_id', $bm->id)->whereYear('date', $year)->sum('cost');
            $maintenanceCost = $vehicle ? Maintenance::where('vehicle_id', $vehicle->id)->whereYear('date', $year)->sum('cost') : 0;
            $totalUsed = $fuelCost + $maintenanceCost;
            $remaining = $yearlyBudget - $totalUsed;
            $usedPercent = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            if ($usedPercent >= 90) {
                $status = 'Increase';
                $suggestedBudget = $yearlyBudget * 1.05;
            } elseif ($usedPercent < 60) {
                $status = 'Decrease';
                $suggestedBudget = $yearlyBudget * 0.85;
            } else {
                $status = 'Maintain';
                $suggestedBudget = $yearlyBudget;
            }

            return [
                'user' => $bm,
                'vehicle' => $vehicle,
                'yearlyBudget' => $yearlyBudget,
                'totalUsed' => $totalUsed,
                'remaining' => $remaining,
                'usedPercent' => $usedPercent,
                'status' => $status,
                'suggestedBudget' => $suggestedBudget,
            ];
        });

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.admin_yearly_pdf', compact(
            'monthlyData',
            'totalLiters',
            'totalCost',
            'highest',
            'topVehicles',
            'boardmembersData',
            'year'
        ));

        $filename = 'admin-dashboard-yearly-' . $year . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export admin monthly PDF
     */
    public function exportAdminMonthlyPdf(Request $request)
    {
        $selectedMonth = (int) $request->input('month', now()->month);
        $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
        $selectedMonthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');
        $year = (int) $request->input('year', now()->year);
        
        $selectedOffice = $request->input('office', null);
        $offices = Office::orderBy('name')->get();
        $officeName = '';

        $query = User::where('role', 'boardmember')
            ->with(['vehicles', 'office.vehicles']);
        
        if ($selectedOffice) {
            $query->where('office_id', $selectedOffice);
            $officeName = Office::find($selectedOffice)?->name ?? '';
        }
        
        $boardmembers = $query->orderBy('name')->get();
        $ids = $boardmembers->pluck('id');

        $totalCostByUser = FuelSlip::whereIn('user_id', $ids)->whereYear('date', $year)
            ->selectRaw('user_id, SUM(cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $maintenanceByVehicle = Maintenance::whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')->groupBy('vehicle_id')->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')->groupBy('user_id')->pluck('total_liters', 'user_id');

        $monthlyCostByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUser, $maintenanceByVehicle, $monthlyLitersByUser, $monthlyCostByUser, $selectedMonth) {
            // Get all vehicles for this board member
            $vehicles = $bm->vehicles()->get();
            
            $fuelCost = (float) ($totalCostByUser[$bm->id] ?? 0);
            $yearlyBudget = self::YEARLY_BUDGET_DEFAULT;
            
            // Get fuel slips per vehicle for this user
            $year = now()->year;
            $fuelSlipsByVehicle = FuelSlip::where('user_id', $bm->id)
                ->whereYear('date', $year)
                ->selectRaw('vehicle_id, SUM(cost) as total_cost, SUM(liters) as total_liters')
                ->groupBy('vehicle_id')
                ->pluck('total_cost', 'vehicle_id');
            
            // Calculate vehicle details
            $vehiclesDetails = $vehicles->map(function($vehicle) use ($maintenanceByVehicle, $fuelSlipsByVehicle) {
                $maintenanceCost = (float) ($maintenanceByVehicle[$vehicle->id] ?? 0);
                $fuelSlipCost = (float) ($fuelSlipsByVehicle[$vehicle->id] ?? 0);
                return [
                    'vehicle' => $vehicle,
                    'maintenanceCost' => $maintenanceCost,
                    'fuelSlipCost' => $fuelSlipCost,
                ];
            })->toArray();
            
            // Calculate totals
            $maintenanceCostTotal = collect($vehiclesDetails)->sum('maintenanceCost');
            $totalUsed = $fuelCost + $maintenanceCostTotal;
            $remainingBudget = $yearlyBudget - $totalUsed;
            $budgetUsedPercentage = $yearlyBudget > 0 ? round(($totalUsed / $yearlyBudget) * 100, 2) : 0;

            $monthlyLitersUsed = (float) ($monthlyLitersByUser[$bm->id] ?? 0);
            $monthlyCostUsed = (float) ($monthlyCostByUser[$bm->id] ?? 0);

            return [
                'user' => $bm,
                'vehicles' => $vehiclesDetails,
                'yearlyBudget' => $yearlyBudget,
                'totalUsed' => $totalUsed,
                'remainingBudget' => $remainingBudget,
                'budgetUsedPercentage' => $budgetUsedPercentage,
                'monthlyLitersUsed' => $monthlyLitersUsed,
                'monthlyCostUsed' => $monthlyCostUsed,
                'fuelCost' => $fuelCost,
                'maintenanceCostTotal' => $maintenanceCostTotal,
            ];
        });

        // Get monthly statistics
        $totalMonthlyLiters = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->sum('liters');
        
        $totalMonthlyCost = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->sum('cost');

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.admin_monthly_pdf', compact(
            'rows',
            'selectedMonth',
            'selectedMonthName',
            'year',
            'officeName',
            'totalMonthlyLiters',
            'totalMonthlyCost'
        ));

        $filename = 'admin-dashboard-' . $selectedMonthName . '-' . $year . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}

