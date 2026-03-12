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
                
                <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
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
            <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            
            <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
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

<script src="{{ asset('js/boardmember-dashboard.js') }}"></script>

@endsection
