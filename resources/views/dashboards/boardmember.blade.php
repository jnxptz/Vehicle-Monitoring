@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/boardmember-dashboard-styles.css') }}">

<style>
/* Fixed Header and Sidebar Layout */
.dashboard-header {
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1100 !important;
}

.dashboard-page {
    padding-top: 70px;
}

/* Fixed Dashboard Nav */
.dashboard-nav {
    position: fixed !important;
    top: 70px;
    left: 0;
    width: 240px;
    height: calc(100vh - 70px) !important;
    overflow-y: auto;
}

.dashboard-container {
    margin-left: 240px;
    overflow-y: auto;
    height: calc(100vh - 70px);
}

/* Mobile overrides */
@media (max-width: 768px) {
    .dashboard-nav {
        display: none !important;
    }
    .dashboard-container {
        margin-left: 0 !important;
        padding: 16px !important;
    }
}

/* Maintenance Alert Styles for Vehicle Pills */
.maintenance-alert-orange {
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%) !important;
    border: 2px solid #ea580c !important;
    color: white !important;
    position: relative;
    animation: pulse-orange 2s infinite;
}

.maintenance-alert-red {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    border: 2px solid #b91c1c !important;
    color: white !important;
    position: relative;
    animation: pulse-red 1.5s infinite;
}

.maintenance-alert-icon {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc2626;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.maintenance-warning-text {
    color: #dc2626 !important;
    font-weight: 600 !important;
    margin-top: 2px;
    display: block;
}

@keyframes pulse-orange {
    0% { box-shadow: 0 0 0 0 rgba(251, 146, 60, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(251, 146, 60, 0); }
    100% { box-shadow: 0 0 0 0 rgba(251, 146, 60, 0); }
}

@keyframes pulse-red {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* Vehicle Card Styles */
.vehicle-pill-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    position: relative;
}

.vehicle-pill-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e1;
}

.vehicle-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}

.vehicle-card-header strong {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
}

.vehicle-type {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}

.maintenance-badge {
    margin-left: auto;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-orange {
    background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
    color: white;
}

.badge-red {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

/* 2-Column Grid Layout */
.vehicle-card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.grid-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.item-label {
    font-size: 10px;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-value {
    font-size: 13px;
    color: #1e293b;
    font-weight: 600;
}

.value-success {
    color: #059669;
    font-weight: 700;
}

.value-danger {
    color: #dc2626;
    font-weight: 700;
}

/* Action Button */
.card-action {
    text-align: right;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
}

.btn-maintenance {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.btn-maintenance:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        max-height: 500px;
        transform: translateY(0);
    }
}

.hidden {
    display: none !important;
}
</style>

@php
use Carbon\Carbon;
@endphp

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>

                
        {{-- Hamburger Menu (Mobile/Tablet Only) --}}
        <div class="hamburger-menu-wrapper">
            <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
            <label for="hamburger-toggle" class="hamburger-btn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            
            <nav class="hamburger-dropdown">
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                
                <div class="bottom-section">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>

            </nav>

        </div>

    </div>

    <div class="dashboard-body">
        <nav class="dashboard-nav">
            @include('partials.sidebar-profile')
            
            <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            
            <div class="bottom-section">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                </form>
            </div>
        </nav>

        <div class="dashboard-container">
            {{-- PAGE HEADER --}}
            <div class="page-header">
                <div>
                    <h2>Welcome, {{ auth()->user()->name }}</h2>
                    <p class="sub-text">
                        @if(isset($vehicles) && $vehicles->count() > 0)
                        @else
                            No vehicle assigned
                        @endif
                    </p>
                </div>
                @if($vehicle)
                    <form method="GET" action="{{ route('boardmember.dashboard') }}" class="filter-bar">
                        <select name="month" onchange="this.form.submit()">
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ isset($selectedMonth) && $month == $selectedMonth ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('boardmember.dashboard.pdf', ['month' => $selectedMonth ?? now()->month]) }}" class="export-btn btn-primary" style="background: linear-gradient(135deg, #ff9b00 0%, #d97706 100%) !important; color: white !important; box-shadow: 0 2px 4px rgba(255, 155, 0, 0.2) !important;" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%) !important'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 155, 0, 0.3) !important';" onmouseout="this.style.background='linear-gradient(135deg, #ff9b00 0%, #d97706 100%) !important'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(255, 155, 0, 0.2) !important';">
                            Export Monthly PDF
                        </a>
                        <a href="{{ route('boardmember.dashboard.yearly.pdf') }}" class="export-btn btn-primary yearly" style="background: linear-gradient(135deg, #ff9b00 0%, #d97706 100%) !important; color: white !important; box-shadow: 0 2px 4px rgba(255, 155, 0, 0.2) !important;" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%) !important'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 155, 0, 0.3) !important';" onmouseout="this.style.background='linear-gradient(135deg, #ff9b00 0%, #d97706 100%) !important'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(255, 155, 0, 0.2) !important';">
                            Export Yearly PDF
                        </a>
                    </form>
                @endif
            </div>

            {{-- Ensure a selected vehicle when multiple exist --}}

            @php
                if ((!isset($vehicle) || !$vehicle) && isset($vehicles) && $vehicles->count() > 0) {
                    $vehicle = $vehicles->first();
                }
            @endphp

            {{-- Alerts (if any) --}}

            @php
                // Preserve alerts from controller, don't overwrite them
                $alerts = $alerts ?? [];
                // Check for fuel limit exceeded
                if(isset($vehicle) && $vehicle) {
                    // Use selected month from filter instead of current month
                    $selectedMonth = (int) request()->input('month', now()->month);
                    $selectedMonth = ($selectedMonth >= 1 && $selectedMonth <= 12) ? $selectedMonth : now()->month;
                    $year = now()->year;
                    
                    // Get fuel slips for selected month

                    $monthlyFuelSlips = \App\Models\FuelSlip::where('user_id', Auth::id())
                        ->where('vehicle_id', $vehicle->id)
                        ->whereMonth('date', $selectedMonth)
                        ->whereYear('date', $year)
                        ->get();
                    $totalLitersUsed = $monthlyFuelSlips->sum('liters');

                    // Check if exceeded monthly fuel limit
                    if($totalLitersUsed > $vehicle->monthly_fuel_limit) {
                        $monthName = Carbon::createFromDate(null, $selectedMonth, 1)->format('F');
                        $alerts[] = "You have exceeded your monthly fuel limit of {$vehicle->monthly_fuel_limit} liters in {$monthName}! Current usage: {$totalLitersUsed} liters.";
                    }
                }
            @endphp

            @if(!empty($alerts))
                <div class="alerts-box">
                    <div>
                        <div>⚠️</div>
                        <h4>Alerts</h4>
                    </div>
                    <ul>
                        @foreach($alerts as $alert)
                            <li>{{ $alert }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- KPI CARDS --}}

            @if(isset($vehicle) && $vehicle)
                <div class="kpi-grid">
                    <div class="kpi-card kpi-card-style">
                        <h4>Yearly Budget</h4>
                        <p>₱{{ number_format($yearlyBudget, 2) }}</p>
                    </div>
                    <div class="kpi-card">
                        <h4>Budget Used</h4>
                        <p>₱{{ number_format($yearlyBudget - $remainingBudget, 2) }}</p>
                    </div>
                    <div class="kpi-card">
                        <h4>Remaining Budget</h4>
                        <p>₱{{ number_format($remainingBudget, 2) }}</p>
                    </div>
                    <div class="kpi-card">
                        <h4>Fuel Used ({{ $selectedMonthName ?? \Carbon\Carbon::now()->format('F') }})</h4>
                        <p>{{ $monthlyLitersUsed }} L</p>
                    </div>
                </div>

                {{-- OVERVIEW CARDS --}}

                <div class="dashboard-sections">
                    <div class="dashboard-card">
                        <h3>Budget Progress</h3>
                        <div>
                            <span>Used</span>
                            <span>{{ $budgetUsedPercentage }}%</span>
                        </div>
                        <div class="budget-bar">
                            <div class="budget-used {{ $budgetUsedPercentage >= 80 ? 'warning' : '' }}" style="width: {{ $budgetUsedPercentage }}%;"></div>
                        </div>
                        <p>₱{{ number_format($yearlyBudget - $remainingBudget, 2) }} used of ₱{{ number_format($yearlyBudget, 2) }}</p>
                    </div>

                    <div class="dashboard-card">
                        <h3>Fuel Consumption</h3>
                        <div>
                            <span>{{ $monthlyLitersUsed }} L / {{ $monthlyLimit }} L</span>
                            @php
                                $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;
                                if ($fuelPercent > 100) $fuelPercent = 100;
                            @endphp
                            <span class="{{ $monthlyLitersUsed > $monthlyLimit ? 'text-danger' : 'text-primary' }}">{{ $fuelPercent }}%</span>
                        </div>
                        <div class="fuel-bar">
                            <div class="fuel-used {{ $monthlyLitersUsed > $monthlyLimit ? 'warning' : '' }}" style="width: {{ $fuelPercent }}%;"></div>
                        </div>
                        @if($monthlyLitersUsed > $monthlyLimit)
                            <p class="warning-text"><strong>⚠ Warning:</strong> Exceeded fuel limit!</p>
                        @endif
                    </div>
                </div>

                {{-- Vehicle Cards --}}

                @if(isset($vehicles) && $vehicles->count() > 0)
                    <div class="vehicle-list">
                        @foreach($vehicles as $v)
                            @php
                                // Get current KM from vehicle or latest fuel slip
                                $currentKm = $v->current_km ?? 0;
                                $latestFuelSlip = \App\Models\FuelSlip::where('vehicle_id', $v->id)
                                    ->orderBy('km_reading', 'desc')
                                    ->first();
                                if ($latestFuelSlip && $latestFuelSlip->km_reading > $currentKm) {
                                    $currentKm = $latestFuelSlip->km_reading;
                                }

                                // Get last maintenance
                                $lastMaintenance = \App\Models\Maintenance::where('vehicle_id', $v->id)
                                    ->orderBy('date', 'desc')
                                    ->first();
                                 
                                $lastMaintenanceKm = $lastMaintenance ? $lastMaintenance->maintenance_km : 0;
                                if ($lastMaintenanceKm == 0) {
                                    $maxKm = \App\Models\Maintenance::where('vehicle_id', $v->id)
                                        ->whereNotNull('maintenance_km')
                                        ->max('maintenance_km');
                                    $lastMaintenanceKm = $maxKm ? (int) $maxKm : 0;
                                }

                                // Calculate KM since last maintenance
                                $kmSinceLastMaintenance = $currentKm - $lastMaintenanceKm;
                                 
                                // Determine alert level and styling
                                $alertClass = '';
                                $alertIcon = '';
                                if ($kmSinceLastMaintenance >= 8000) {
                                    $alertClass = 'maintenance-alert-red';
                                    $alertIcon = '!';
                                } elseif ($kmSinceLastMaintenance >= 4500) {
                                    $alertClass = 'maintenance-alert-orange';
                                    $alertIcon = '!';
                                } elseif ($kmSinceLastMaintenance >= 5000) {
                                    $alertClass = 'maintenance-alert-orange';
                                    $alertIcon = '!';
                                }
                            @endphp
                            <div class="vehicle-pill-card {{ $alertClass }}">
                                @if($alertIcon)
                                    <span class="maintenance-alert-icon">{{ $alertIcon }}</span>
                                @endif
                                <div class="vehicle-card-header">
                                    <strong>{{ $v->plate_number }}</strong>
                                    <span class="vehicle-type">{{ $v->make ?? '' }} {{ $v->model ?? '' }}</span>
                                    @if($kmSinceLastMaintenance >= 4500)
                                        <span class="maintenance-badge {{ $kmSinceLastMaintenance >= 8000 ? 'badge-red' : 'badge-orange' }}">
                                            {{ $kmSinceLastMaintenance >= 8000 ? 'Overdue!' : 'Due Soon' }}
                                        </span>
                                    @endif
                                </div>
                                 
                                <div class="vehicle-card-grid">
                                    <div class="grid-item">
                                        <span class="item-label">Current KM</span>
                                        <span class="item-value">{{ number_format($currentKm) }} km</span>
                                    </div>
                                    <div class="grid-item">
                                        <span class="item-label">Last Maintenance</span>
                                        <span class="item-value">{{ $lastMaintenance ? $lastMaintenance->date : 'N/A' }}</span>
                                    </div>
                                    <div class="grid-item">
                                        <span class="item-label">Last Maint. KM</span>
                                        <span class="item-value">{{ $lastMaintenanceKm ? number_format($lastMaintenanceKm) . ' km' : 'N/A' }}</span>
                                    </div>
                                    <div class="grid-item">
                                        <span class="item-label">KM Since Last</span>
                                        <span class="item-value {{ $kmSinceLastMaintenance >= 4500 ? 'value-danger' : 'value-success' }}">
                                            {{ number_format($kmSinceLastMaintenance) }} km
                                        </span>
                                    </div>
                                    <div class="grid-item">
                                        <span class="item-label">Driver</span>
                                        <span class="item-value">{{ auth()->user()->name }}</span>
                                    </div>
                                    <div class="grid-item">
                                        <span class="item-label">Status</span>
                                        <span class="item-value {{ $kmSinceLastMaintenance >= 4500 ? 'value-danger' : 'value-success' }}">
                                            {{ $kmSinceLastMaintenance >= 4500 ? 'Needs Service' : 'Good' }}
                                        </span>
                                    </div>
                                </div>

                                @if($kmSinceLastMaintenance >= 4500)
                                    <div class="card-action">
                                        <a href="{{ route('maintenances.index') }}" class="btn-maintenance">
                                            Schedule Maintenance →
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @else

                <div class="no-vehicle">
                    <div class="icon">🚗</div>
                    <h4>No vehicle assigned</h4>
                    <p>Please contact your administrator to assign a vehicle to your account.</p>
                </div>
            @endif
        </div> 
    </div> 
</div>

<script>
// No toggle functionality needed - cards display all information directly
</script>

<script src="{{ asset('js/boardmember-dashboard.js') }}"></script>
@endsection
