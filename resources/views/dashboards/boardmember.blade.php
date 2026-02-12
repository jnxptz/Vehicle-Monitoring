@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">

    
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
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

                        <a href="{{ route('boardmember.dashboard.yearly.pdf') }}" class="export-btn btn-primary">
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
            @if(!empty($alerts))
                <div class="alerts-box" style="margin-bottom:12px; padding:12px 16px; background:#fff4e5; border:1px solid #f3d9b6; border-radius:6px;">
                    <h4 style="margin:0 0 8px 0; font-size:15px;">Alerts</h4>
                    <ul style="margin:0; padding-left:18px; color:#5b6168;">
                        @foreach($alerts as $alert)
                            <li>{{ $alert }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Vehicle list (boardmember may have multiple vehicles) --}}
            @if(isset($vehicles) && $vehicles->count() > 0)
                <div class="vehicle-list" style="display:flex; gap:12px; margin:8px 0 16px 0; flex-wrap:wrap;">
                    @foreach($vehicles as $v)
                        <div class="vehicle-pill" style="padding:10px 14px; border:1px solid #e6eef8; border-radius:8px; background: {{ (isset($vehicle) && $vehicle->id == $v->id) ? '#e7f3ff' : '#fff' }}; min-width:140px;">
                            <strong style="display:block; color:#0b2e66;">{{ $v->plate_number }}</strong>
                            <small style="color:#6b7280;">{{ $v->make ?? '' }} {{ $v->model ?? '' }}</small>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- KPI CARDS --}}
            @if(isset($vehicle) && $vehicle)
                <div class="kpi-grid">
                    <div class="kpi-card">
                        <h4>Yearly Budget</h4>
                        <p>₱{{ number_format($yearlyBudget, 2) }}</p>
                    </div>

                    <div class="kpi-card">
                        <h4>Budget Used (YTD)</h4>
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
                        <p style="margin: 12px 0;"><strong>Used:</strong> {{ $budgetUsedPercentage }}%</p>
                        <div class="budget-bar">
                            <div class="budget-used {{ $budgetUsedPercentage >= 80 ? 'warning' : '' }}" style="width: {{ $budgetUsedPercentage }}%;"></div>
                        </div>
                    </div>

                    
                    <div class="dashboard-card">
                        <h3>Fuel Consumption</h3>
                        <p style="margin: 12px 0;"><strong>Limit:</strong> {{ $monthlyLimit }} L | <strong>Used:</strong> {{ $monthlyLitersUsed }} L</p>
                        @php
                            $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;
                            if ($fuelPercent > 100) $fuelPercent = 100;
                        @endphp
                        <div class="fuel-bar">
                            <div class="fuel-used {{ $monthlyLitersUsed > $monthlyLimit ? 'warning' : '' }}" style="width: {{ $fuelPercent }}%;"></div>
                        </div>
                        @if($monthlyLitersUsed > $monthlyLimit)
                            <p class="warning-text" style="margin-top: 8px;"><strong>⚠ Warning:</strong> Exceeded fuel limit!</p>
                        @endif
                    </div>
                </div>
            @else
                <div style="padding: 32px; text-align: center; background: #f9fafb; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <p style="font-size: 15px; color: #6b7280;">No vehicle assigned to your account.</p>
                </div>
            @endif

        </div> 
    </div> 
</div>

<script>
    // Close hamburger menu when a link is clicked
    document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('hamburger-toggle').checked = false;
        });
    });

    // Also handle form submission (logout)
    document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
        form.addEventListener('submit', () => {
            document.getElementById('hamburger-toggle').checked = false;
        });
    });
</script>
@endsection
