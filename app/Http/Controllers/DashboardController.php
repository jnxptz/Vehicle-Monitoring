<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Vehicle;
use App\Models\FuelSlip;
use App\Models\Maintenance;
use App\Models\User;
use App\Models\Office;
use App\Mail\MaintenanceDueMail;
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
            ->sum('total_cost');

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
            ->with(['vehicles', 'office.vehicles', 'bm']);
        
        if ($selectedOffice) {
            $query->where('office_id', $selectedOffice);
        }
        
        $boardmembers = $query->orderBy('name')->get();
        $ids = $boardmembers->pluck('id');

        $totalCostByUser = FuelSlip::whereIn('user_id', $ids)->whereYear('date', $year)
            ->selectRaw('user_id, SUM(total_cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $maintenanceByVehicle = Maintenance::whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')->groupBy('vehicle_id')->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')->groupBy('user_id')->pluck('total_liters', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUser, $maintenanceByVehicle, $monthlyLitersByUser) {
            // Get all vehicles for this board member
            $vehicles = $bm->vehicles()->get();
            
            $fuelCost = (float) ($totalCostByUser[$bm->id] ?? 0);
            
            // Fix: Access budget through bm relationship
            $yearlyBudget = $bm->bm ? $bm->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            
            // Debug logging
            \Log::info('Dashboard - User ' . $bm->id . ' BM loaded: ' . ($bm->bm ? 'yes' : 'no') . ' Budget: ' . $yearlyBudget);
            
            // Get fuel slips per vehicle for this user
            $year = now()->year;
            $fuelSlipsByVehicle = FuelSlip::where('user_id', $bm->id)
                ->whereYear('date', $year)
                ->selectRaw('vehicle_id, SUM(total_cost) as total_cost, SUM(liters) as total_liters')
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
     * Get start and end months for reports
     */
    private function getReportMonthRange(string $reportType, ?string $monthRange): array
    {
        $startMonth = 1;
        $endMonth = 12;

        switch ($reportType) {
            case 'current-month':
                $startMonth = now()->month;
                $endMonth = now()->month;
                break;
            case 'single-month':
                $startMonth = $monthRange ?: now()->month;
                $endMonth = $startMonth;
                break;
            case 'quarterly':
                $currentQuarter = ceil(now()->month / 3);
                $startMonth = ($currentQuarter - 1) * 3 + 1;
                $endMonth = $currentQuarter * 3;
                break;
            case 'semester':
                $startMonth = now()->month <= 6 ? 1 : 7;
                $endMonth = now()->month <= 6 ? 6 : 12;
                break;
            case 'custom-range':
                if ($monthRange && strpos($monthRange, '-') !== false) {
                    list($startMonth, $endMonth) = explode('-', $monthRange);
                } else {
                    // Default to current month if custom range is selected but no range is chose
                    $startMonth = now()->month;
                    $endMonth = now()->month;
                    // Note: If they want January - February as default, we could use that instead
                    // but current month is safer.
                }
                break;
        }

        return [(int) $startMonth, (int) $endMonth];
    }

    /**
     * Admin reports page for boardmember comparison and analytics
     */
    public function reports(Request $request)
    {
        $year = $request->input('year', now()->year);
        $reportType = $request->input('report_type', 'current-month');
        $monthRange = $request->input('month_range', null);

        // Get all boardmembers
        $boardmembers = User::where('role', 'boardmember')
            ->with(['office', 'vehicles'])
            ->orderBy('name')
            ->get();

        $ids = $boardmembers->pluck('id');

        // Calculate date range based on report type
        list($startMonth, $endMonth) = $this->getReportMonthRange($reportType, $monthRange);

        // Get fuel slips data for the period
        $fuelQuery = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year);

        if ($startMonth == $endMonth) {
            $fuelQuery->whereMonth('date', $startMonth);
        } else {
            $fuelQuery->whereBetween('date', [
                Carbon::createFromDate($year, $startMonth, 1)->startOfMonth(),
                Carbon::createFromDate($year, $endMonth, 1)->endOfMonth()
            ]);
        }

        $fuelCostByUser = $fuelQuery
            ->selectRaw('user_id, SUM(total_cost) as total_cost')
            ->groupBy('user_id')
            ->pluck('total_cost', 'user_id');

        // Get all vehicle IDs for these boardmembers
        $vehicleIds = Vehicle::whereIn('bm_id', $ids)->pluck('id');

        // Get maintenance data for the period
        $maintenanceQuery = Maintenance::whereIn('vehicle_id', $vehicleIds)
            ->whereYear('date', $year);

        if ($startMonth == $endMonth) {
            $maintenanceQuery->whereMonth('date', $startMonth);
        } else {
            $maintenanceQuery->whereBetween('date', [
                Carbon::createFromDate($year, $startMonth, 1)->startOfMonth(),
                Carbon::createFromDate($year, $endMonth, 1)->endOfMonth()
            ]);
        }

        $maintenanceCostByVehicle = $maintenanceQuery
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')
            ->groupBy('vehicle_id')
            ->pluck('total_cost', 'vehicle_id');

        // Build boardmember stats
        $boardmemberStats = [];
        foreach ($boardmembers as $bm) {
            $vehicles = $bm->vehicles;
            $maintenanceCost = 0;

            foreach ($vehicles as $vehicle) {
                $maintenanceCost += (float) ($maintenanceCostByVehicle[$vehicle->id] ?? 0);
            }

            $boardmemberStats[$bm->id] = [
                'name' => $bm->name,
                'office' => $bm->office?->name,
                'fuelSlipCost' => (float) ($fuelCostByUser[$bm->id] ?? 0),
                'maintenanceCost' => $maintenanceCost,
            ];
        }

        $periodLabel = match($reportType) {
            'current-month' => Carbon::createFromDate(null, $startMonth, 1)->format('F Y'),
            'single-month' => Carbon::createFromDate(null, $startMonth, 1)->format('F Y'),
            'quarterly' => "Q" . ceil($startMonth/3) . " $year",
            'semester' => ($startMonth == 1 ? 'First' : 'Second') . " Semester $year",
            'custom-range' => Carbon::createFromDate(null, (int)$startMonth, 1)->format('F') . ' - ' . Carbon::createFromDate(null, (int)$endMonth, 1)->format('F Y'),
            default => "$year"
        };

        return view('dashboards.reports', compact(
            'boardmemberStats',
            'year',
            'reportType',
            'monthRange',
            'periodLabel',
            'startMonth',
            'endMonth'
        ));
    }

    /**
     * Boardmember dashboard showing their vehicle and budget
     */
    public function boardmember(Request $request)
    {
        $user = Auth::user();
        // Load bm relationship to get budget data
        $user->load('bm');
        // fetch all vehicles for this boardmember so the view can list them
        $vehicles = $user->vehicles()->get();
        // prefer user's direct vehicle, otherwise use the first vehicle from their office
        $vehicle = $user->vehicles()->first() ? $user->vehicles()->first() : ($user->office ? $user->office->vehicles()->first() : null);

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
            // Fix: Access budget through bm relationship like in admin method
            $yearlyBudget = $user->bm ? $user->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            // Use latest km for this specific vehicle (not user's across all vehicles)
            $latestKm = (int) FuelSlip::where('vehicle_id', $vehicle->id)->max('km_reading');
            $currentKm = max((int) ($vehicle->current_km ?? 0), $latestKm);

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('total_cost');
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

            $lastMaintenanceType = $lastMaintenance ? $lastMaintenance->maintenance_type : 'N/A';

            $nextDueKm = ($lastMaintenanceKm > 0) ? ($lastMaintenanceKm + 5000) : 5000;
            
            // Debug: Log maintenance calculation
            \Log::info("Maintenance Alert Debug - Vehicle: {$vehicle->id}, Current KM: {$currentKm}, Last Maintenance KM: {$lastMaintenanceKm}, Next Due KM: {$nextDueKm}, Last Maintenance Type: {$lastMaintenanceType}");
            
            if ($currentKm >= $nextDueKm) {
                $alerts[] = "⚠️ Preventive maintenance due: last ({$lastMaintenanceType}) at {$lastMaintenanceKm} km, next at {$nextDueKm} km, current: {$currentKm} km.";
                
                // Send email notification for maintenance due
                $emailKey = 'maintenance_due_email_sent_' . $vehicle->id . '_' . date('Y-m-d');
                if (!session()->has($emailKey) && $user->email) {
                    try {
                        Mail::to($user->email)->send(new MaintenanceDueMail($vehicle, $currentKm, $lastMaintenanceKm, $nextDueKm, $lastMaintenanceType));
                        \Log::info('Maintenance due email sent to: ' . $user->email . ' for vehicle: ' . $vehicle->plate_number);
                        session()->put($emailKey, true);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send maintenance due email: ' . $e->getMessage());
                    }
                }
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
        // Load bm relationship to get budget data
        $user->load('bm');
        // prefer user's direct vehicle, otherwise use first vehicle from their office
        $vehicle = $user->vehicles()->first() ? $user->vehicles()->first() : ($user->office ? $user->office->vehicles()->first() : null);

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
            // Fix: Access budget through bm relationship like in admin method
            $yearlyBudget = $user->bm ? $user->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('total_cost');
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
        // Load bm relationship to get budget data
        $user->load('bm');
        // prefer user's direct vehicle, otherwise use first vehicle from their office
        $vehicle = $user->vehicle ? $user->vehicle : ($user->office ? $user->office->vehicles()->first() : null);

        $yearlyBudget = 0;
        $remainingBudget = 0;
        $budgetUsedPercentage = 0;
        $monthlyLimit = 0;
        $monthlyData = [];
        $alerts = [];

        if ($vehicle) {
            // Fix: Access budget through bm relationship like in admin method
            $yearlyBudget = $user->bm ? $user->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            $monthlyLimit = $vehicle->monthly_fuel_limit ?? 100;

            $fuelCost = FuelSlip::where('user_id', $user->id)->whereYear('date', now()->year)->sum('total_cost');
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
                    ->sum('total_cost');

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

    
    public function exportAdminYearlyPdf(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $selectedOffice = $request->input('office', null);
        $officeName = $selectedOffice ? (Office::find($selectedOffice)?->name ?? '') : '';

        
        $monthlyData = [];
        $totalLiters = 0;
        $totalCost = 0;

        for ($month = 1; $month <= 12; $month++) {
            $monthName = Carbon::createFromDate(null, $month, 1)->format('F');
            $monthlyLiters = FuelSlip::whereYear('date', $year)
                ->when($selectedOffice, function ($query) use ($selectedOffice) {
                    return $query->whereHas('user', function ($q) use ($selectedOffice) {
                        $q->where('office_id', $selectedOffice);
                    });
                })
                ->whereMonth('date', $month)
                ->sum('liters');
            $monthlyCost = FuelSlip::whereYear('date', $year)
                ->when($selectedOffice, function ($query) use ($selectedOffice) {
                    return $query->whereHas('user', function ($q) use ($selectedOffice) {
                        $q->where('office_id', $selectedOffice);
                    });
                })
                ->whereMonth('date', $month)
                ->sum('total_cost');

            $monthlyData[] = [
                'month' => $monthName,
                'liters' => $monthlyLiters,
                'cost' => $monthlyCost,
            ];

            $totalLiters += $monthlyLiters;
            $totalCost += $monthlyCost;
        }

        
        $highest = collect($monthlyData)->sortByDesc('liters')->first();

        
        $vehicles = Vehicle::with('bm')
            ->when($selectedOffice, function ($query) use ($selectedOffice) {
                return $query->where('office_id', $selectedOffice);
            })
            ->get();
        $topVehicles = $vehicles->map(function ($v) use ($year) {
            $vLiters = FuelSlip::where('vehicle_id', $v->id)
                ->whereYear('date', $year)
                ->sum('liters');
            $vCost = FuelSlip::where('vehicle_id', $v->id)
                ->whereYear('date', $year)
                ->sum('total_cost');
            return ['vehicle' => $v, 'liters' => $vLiters, 'fuelCost' => $vCost];
        })->filter(fn($v) => $v['liters'] > 0)
          ->sortByDesc('liters')
          ->values();

        
        $boardmembers = User::where('role', 'boardmember')
            ->with(['vehicles', 'office.vehicles'])
            ->when($selectedOffice, function ($query) use ($selectedOffice) {
                return $query->where('office_id', $selectedOffice);
            })
            ->orderBy('name')
            ->get();

        $monthlyFuelByOffice = FuelSlip::query()
            ->join('users', 'fuel_slips.user_id', '=', 'users.id')
            ->leftJoin('offices', 'users.office_id', '=', 'offices.id')
            ->whereYear('fuel_slips.date', $year)
            ->when($selectedOffice, function ($query) use ($selectedOffice) {
                return $query->where('users.office_id', $selectedOffice);
            })
            ->selectRaw("
                users.office_id,
                COALESCE(offices.name, 'No Office') as office,
                MONTH(fuel_slips.date) as month_number,
                SUM(fuel_slips.liters) as liters,
                SUM(fuel_slips.total_cost) as cost
            ")
            ->groupBy('users.office_id', 'offices.name', 'month_number')
            ->get()
            ->map(function ($monthData) {
                return [
                    'office' => $monthData->office,
                    'month' => Carbon::createFromDate(null, (int) $monthData->month_number, 1)->format('F'),
                    'monthNumber' => (int) $monthData->month_number,
                    'liters' => (float) $monthData->liters,
                    'cost' => (float) $monthData->cost,
                ];
            })
            ->sortBy(function ($row) {
                return strtolower($row['office']) . '-' . str_pad($row['monthNumber'], 2, '0', STR_PAD_LEFT);
            })
            ->values();

        $boardmembersData = $boardmembers->map(function ($bm) use ($year) {
            // prefer user's direct vehicle, otherwise fall back to their office's first vehicle
            $vehicle = $bm->vehicle ?? ($bm->office?->vehicles->first() ?? null);
            $yearlyBudget = $bm->bm ? $bm->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            
            // Debug logging
            \Log::info('Dashboard - User ' . $bm->id . ' BM loaded: ' . ($bm->bm ? 'yes' : 'no') . ' Budget: ' . $yearlyBudget);

            $fuelCost = FuelSlip::where('user_id', $bm->id)->whereYear('date', $year)->sum('total_cost');
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

        // Gather Maintenance Data
        $maintenanceRecords = Maintenance::whereYear('date', $year)
            ->with('vehicle', 'vehicle.office', 'vehicle.bm')
            ->when($selectedOffice, function ($query) use ($selectedOffice) {
                return $query->whereHas('vehicle', function ($q) use ($selectedOffice) {
                    $q->where('office_id', $selectedOffice);
                });
            })
            ->orderBy('date', 'desc')
            ->get();

        $totalMaintenanceCost = $maintenanceRecords->sum('cost');

        // Maintenance by type
        $maintenanceByType = $maintenanceRecords->groupBy('maintenance_type')
            ->map(function ($items) {
                return [
                    'type' => $items->first()->maintenance_type,
                    'count' => $items->count(),
                    'totalCost' => $items->sum('cost'),
                    'averageCost' => $items->avg('cost'),
                ];
            })
            ->sortByDesc('totalCost')
            ->values();

        // Vehicles with high maintenance costs
        $vehiclesWithHighMaintenance = $maintenanceRecords->groupBy('vehicle_id')
            ->map(function ($items) {
                $vehicle = $items->first()->vehicle;
                return [
                    'vehicle' => $vehicle,
                    'maintenanceCount' => $items->count(),
                    'totalCost' => $items->sum('cost'),
                    'averageCost' => $items->avg('cost'),
                ];
            })
            ->sortByDesc('totalCost')
            ->take(10)
            ->values();

        // Overdue or pending maintenance (based on maintenance_km intervals)
        $vehiclesNeedingAttention = Vehicle::with('maintenances', 'fuelSlips')
            ->when($selectedOffice, function ($query) use ($selectedOffice) {
                return $query->where('office_id', $selectedOffice);
            })
            ->get()
            ->filter(function ($vehicle) use ($year) {
                $latestFuelSlip = $vehicle->fuelSlips()->latest('date')->first();
                $latestMaintenance = $vehicle->maintenances()->latest('date')->first();
                
                if (!$latestFuelSlip) return false;
                
                $currentKm = $latestFuelSlip->km_reading ?? $vehicle->current_km ?? 0;
                $lastMaintenanceKm = $latestMaintenance ? $latestMaintenance->maintenance_km : 0;
                $kmSinceLastMaintenance = $currentKm - $lastMaintenanceKm;
                
                // Flag if more than 5000 km since last maintenance
                return $kmSinceLastMaintenance > 5000;
            })
            ->sortBy(function ($v) {
                $latestFuelSlip = $v->fuelSlips()->latest('date')->first();
                $latestMaintenance = $v->maintenances()->latest('date')->first();
                $currentKm = $latestFuelSlip->km_reading ?? $v->current_km ?? 0;
                $lastMaintenanceKm = $latestMaintenance ? $latestMaintenance->maintenance_km : 0;
                return -($currentKm - $lastMaintenanceKm);
            })
            ->take(5)
            ->values();

        // Financial Summary
        $grandTotalExpense = $totalCost + $totalMaintenanceCost;
        $totalBudgetAllocated = $boardmembersData->sum('yearlyBudget');
        $totalBudgetUsed = $boardmembersData->sum('totalUsed');
        $totalBudgetRemaining = $totalBudgetAllocated - $totalBudgetUsed;
        $budgetUtilizationPercent = $totalBudgetAllocated > 0 
            ? round(($totalBudgetUsed / $totalBudgetAllocated) * 100, 2)
            : 0;

        // Identify high-cost vehicles
        $highCostVehicles = $vehicles->map(function ($v) use ($year) {
            $fuelCost = FuelSlip::where('vehicle_id', $v->id)
                ->whereYear('date', $year)
                ->sum('total_cost');
            $maintenanceCost = Maintenance::where('vehicle_id', $v->id)
                ->whereYear('date', $year)
                ->sum('cost');
            $totalCost = $fuelCost + $maintenanceCost;
            
            return [
                'vehicle' => $v,
                'fuelCost' => $fuelCost,
                'maintenanceCost' => $maintenanceCost,
                'totalCost' => $totalCost,
                'costPerKm' => 0, // Will calculate if we have km data
            ];
        })->filter(fn($v) => $v['totalCost'] > 0)
          ->sortByDesc('totalCost')
          ->take(10)
          ->values();

        // Board members with budget alerts
        $boardmembersWithAlerts = $boardmembersData->filter(function ($bm) {
            return $bm['usedPercent'] >= 85; // 85% or higher usage
        })->sortByDesc('usedPercent')->values();

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.admin_yearly_pdf', compact(
            'monthlyData',
            'totalLiters',
            'totalCost',
            'totalMaintenanceCost',
            'highest',
            'topVehicles',
            'vehicles',
            'boardmembersData',
            'monthlyFuelByOffice',
            'officeName',
            'year',
            'maintenanceRecords',
            'maintenanceByType',
            'vehiclesWithHighMaintenance',
            'vehiclesNeedingAttention',
            'grandTotalExpense',
            'totalBudgetAllocated',
            'totalBudgetUsed',
            'totalBudgetRemaining',
            'budgetUtilizationPercent',
            'highCostVehicles',
            'boardmembersWithAlerts'
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
            ->selectRaw('user_id, SUM(total_cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $maintenanceByVehicle = Maintenance::whereYear('date', $year)
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')->groupBy('vehicle_id')->pluck('total_cost', 'vehicle_id');

        $monthlyLitersByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(liters) as total_liters')->groupBy('user_id')->pluck('total_liters', 'user_id');

        $monthlyCostByUser = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year)->whereMonth('date', $selectedMonth)
            ->selectRaw('user_id, SUM(total_cost) as total_cost')->groupBy('user_id')->pluck('total_cost', 'user_id');

        $rows = $boardmembers->map(function ($bm) use ($totalCostByUser, $maintenanceByVehicle, $monthlyLitersByUser, $monthlyCostByUser, $selectedMonth) {
            // Get all vehicles for this board member
            $vehicles = $bm->vehicles()->get();
            
            $fuelCost = (float) ($totalCostByUser[$bm->id] ?? 0);
            $yearlyBudget = $bm->bm ? $bm->bm->yearly_budget : self::YEARLY_BUDGET_DEFAULT;
            
            // Get fuel slips per vehicle for this user
            $year = now()->year;
            $fuelSlipsByVehicle = FuelSlip::where('user_id', $bm->id)
                ->whereYear('date', $year)
                ->selectRaw('vehicle_id, SUM(total_cost) as total_cost, SUM(liters) as total_liters')
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
            ->sum('total_cost');

        // Analysis Data
        $highestMonthlySpender = $rows->sortByDesc('monthlyCostUsed')->first();
        $highestFuelUsage = $rows->sortByDesc('monthlyLitersUsed')->first();
        
        // Find highest vehicle expense and identify it
        $allVehicleCosts = [];
        foreach ($rows as $row) {
            foreach ($row['vehicles'] as $vehicle) {
                $totalVehicleCost = $vehicle['fuelSlipCost'] + $vehicle['maintenanceCost'];
                $allVehicleCosts[] = [
                    'vehicle' => $vehicle['vehicle'],
                    'boardMember' => $row['user']->name,
                    'fuelCost' => $vehicle['fuelSlipCost'],
                    'maintenanceCost' => $vehicle['maintenanceCost'],
                    'totalCost' => $totalVehicleCost,
                ];
            }
        }
        usort($allVehicleCosts, fn($a, $b) => $b['totalCost'] <=> $a['totalCost']);
        $highestVehicleExpense = $allVehicleCosts[0] ?? null;

        // Find vehicles with high fuel or maintenance costs
        $highFuelCostVehicles = collect($allVehicleCosts)
            ->filter(fn($v) => $v['fuelCost'] > ($totalMonthlyCost / count($allVehicleCosts) * 1.5))
            ->take(3)
            ->values();
        
        $highMaintenanceCostVehicles = collect($allVehicleCosts)
            ->filter(fn($v) => $v['maintenanceCost'] > 0)
            ->sortByDesc('maintenanceCost')
            ->take(3)
            ->values();

        // Board members with budget concerns
        $boardMembersNearLimit = $rows->filter(fn($bm) => $bm['budgetUsedPercentage'] >= 80)
            ->sortByDesc('budgetUsedPercentage');
        
        $boardMembersExceeded = $rows->filter(fn($bm) => $bm['remainingBudget'] < 0);
        
        $boardMembersNoUsage = $rows->filter(fn($bm) => $bm['monthlyLitersUsed'] == 0);

        // Monthly usage assessment
        $averageMonthlyCost = $totalMonthlyCost / max(1, $rows->count());
        $usageAssessment = 'Normal';
        if ($totalMonthlyCost > $averageMonthlyCost * 1.3) {
            $usageAssessment = 'High - Review recommended';
        } elseif ($totalMonthlyCost < $averageMonthlyCost * 0.7) {
            $usageAssessment = 'Low - Below average';
        }

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.admin_monthly_pdf', compact(
            'rows',
            'selectedMonth',
            'selectedMonthName',
            'year',
            'officeName',
            'totalMonthlyLiters',
            'totalMonthlyCost',
            'highestMonthlySpender',
            'highestFuelUsage',
            'highestVehicleExpense',
            'highFuelCostVehicles',
            'highMaintenanceCostVehicles',
            'boardMembersNearLimit',
            'boardMembersExceeded',
            'boardMembersNoUsage',
            'usageAssessment',
            'averageMonthlyCost'
        ));

        $filename = 'admin-dashboard-' . $selectedMonthName . '-' . $year . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export reports PDF
     */
    public function exportReportsPdf(Request $request)
    {
        $year = $request->input('year', now()->year);
        $reportType = $request->input('report_type', 'current-month');
        $monthRange = $request->input('month_range', null);

        // Get all boardmembers
        $boardmembers = User::where('role', 'boardmember')
            ->with(['office', 'vehicles'])
            ->orderBy('name')
            ->get();

        $ids = $boardmembers->pluck('id');

        // Calculate date range based on report type
        list($startMonth, $endMonth) = $this->getReportMonthRange($reportType, $monthRange);

        // Get fuel slips data for the period
        $fuelQuery = FuelSlip::whereIn('user_id', $ids)
            ->whereYear('date', $year);

        if ($startMonth == $endMonth) {
            $fuelQuery->whereMonth('date', $startMonth);
        } else {
            $fuelQuery->whereBetween('date', [
                Carbon::createFromDate($year, $startMonth, 1)->startOfMonth(),
                Carbon::createFromDate($year, $endMonth, 1)->endOfMonth()
            ]);
        }

        $fuelCostByUser = $fuelQuery
            ->selectRaw('user_id, SUM(total_cost) as total_cost')
            ->groupBy('user_id')
            ->pluck('total_cost', 'user_id');

        // Get all vehicle IDs for these boardmembers
        $vehicleIds = Vehicle::whereIn('bm_id', $ids)->pluck('id');

        // Get maintenance data for the period
        $maintenanceQuery = Maintenance::whereIn('vehicle_id', $vehicleIds)
            ->whereYear('date', $year);

        if ($startMonth == $endMonth) {
            $maintenanceQuery->whereMonth('date', $startMonth);
        } else {
            $maintenanceQuery->whereBetween('date', [
                Carbon::createFromDate($year, $startMonth, 1)->startOfMonth(),
                Carbon::createFromDate($year, $endMonth, 1)->endOfMonth()
            ]);
        }

        $maintenanceCostByVehicle = $maintenanceQuery
            ->selectRaw('vehicle_id, SUM(cost) as total_cost')
            ->groupBy('vehicle_id')
            ->pluck('total_cost', 'vehicle_id');

        // Build boardmember stats
        $boardmemberStats = [];
        foreach ($boardmembers as $bm) {
            $vehicles = $bm->vehicles;
            $maintenanceCost = 0;

            foreach ($vehicles as $vehicle) {
                $maintenanceCost += (float) ($maintenanceCostByVehicle[$vehicle->id] ?? 0);
            }

            $boardmemberStats[$bm->id] = [
                'name' => $bm->name,
                'office' => $bm->office?->name,
                'fuelSlipCost' => (float) ($fuelCostByUser[$bm->id] ?? 0),
                'maintenanceCost' => $maintenanceCost,
            ];
        }

        $periodLabel = match($reportType) {
            'current-month' => Carbon::createFromDate(null, $startMonth, 1)->format('F Y'),
            'single-month' => Carbon::createFromDate(null, $startMonth, 1)->format('F Y'),
            'quarterly' => "Q" . ceil($startMonth/3) . " $year",
            'semester' => ($startMonth == 1 ? 'First' : 'Second') . " Semester $year",
            'custom-range' => Carbon::createFromDate(null, (int)$startMonth, 1)->format('F') . ' - ' . Carbon::createFromDate(null, (int)$endMonth, 1)->format('F Y'),
            default => "$year"
        };

        // Generate PDF
        $pdf = Pdf::loadView('dashboards.reports_pdf', compact(
            'boardmemberStats',
            'year',
            'reportType',
            'monthRange',
            'periodLabel',
            'startMonth',
            'endMonth'
        ));

        $filename = 'reports-' . $periodLabel . '-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
