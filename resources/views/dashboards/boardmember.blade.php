@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/boardmember-dashboard-styles.css') }}">

@php
use Carbon\Carbon;
@endphp

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>

        @include('partials.user-profile-dropdown')
        
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

                    <h2>Welcome, {{ Auth::user()->name }}!</h2>

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

                        
                        <a href="{{ route('boardmember.dashboard.pdf', ['month' => $selectedMonth ?? now()->month]) }}" class="export-btn btn-primary">
                            Export Monthly PDF
                        </a>

                        
                        <a href="{{ route('boardmember.dashboard.yearly.pdf') }}" class="export-btn btn-primary yearly">
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

                $alerts = [];

                

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

            {{-- Vehicle list (boardmember may have multiple vehicles) --}}

            @if(isset($vehicles) && $vehicles->count() > 0)

                <div class="vehicle-list">
                    @foreach($vehicles as $v)
                        <div class="vehicle-pill">
                            <strong>{{ $v->plate_number }}</strong>
                            <small>{{ $v->make ?? '' }} {{ $v->model ?? '' }}</small>
                        </div>
                    @endforeach
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

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>

<script src="{{ asset('js/boardmember-dashboard.js') }}"></script>

@endsection
