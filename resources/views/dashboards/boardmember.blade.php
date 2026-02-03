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
            @if(!$vehicle)
                <a href="{{ route('vehicles.create') }}" class="{{ request()->routeIs('vehicles.create') ? 'active' : '' }}">Register Vehicle</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </nav>

       
        <div class="dashboard-container">

          
            <h2>Welcome, {{ Auth::user()->name }}!</h2>

          
            @if(!empty($alerts))
                <div class="alerts-box">
                    <h3>Alerts:</h3>
                    <ul>
                        @foreach($alerts as $alert)
                            <li>{{ $alert }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($vehicle)
                <p><strong>Vehicle Plate Number:</strong> {{ $vehicle->plate_number }}</p>

               
                <form method="GET" action="{{ route('boardmember.dashboard') }}" style="margin: 15px 0;">
                    <label for="month" style="font-weight: 500; margin-right: 8px;">Select month:</label>
                    <select id="month" name="month" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ isset($selectedMonth) && $month == $selectedMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    @isset($selectedMonthName)
                        <span style="margin-left: 10px; font-size: 14px; color: #607d8b;">
                            Showing data for <strong>{{ $selectedMonthName }}</strong>
                        </span>
                    @endisset
                </form>

               
                <div style="margin: 10px 0 20px;">
                    <a class="btn-primary" href="{{ route('boardmember.dashboard.pdf', ['month' => $selectedMonth ?? now()->month]) }}">
                        Export Dashboard PDF
                    </a>
                </div>

               
                <div class="dashboard-sections">

                    
                    <div class="dashboard-card">
                        <h3>Budget Overview</h3>
                        <p><strong>Yearly Budget:</strong> ₱{{ number_format($yearlyBudget, 2) }}</p>
                        <p><strong>Remaining Budget:</strong> ₱{{ number_format($remainingBudget, 2) }}</p>
                        <p><strong>Budget Used:</strong> {{ $budgetUsedPercentage }}%</p>
                        <div class="budget-bar">
                            <div class="budget-used {{ $budgetUsedPercentage >= 80 ? 'warning' : '' }}" style="width: {{ $budgetUsedPercentage }}%;"></div>
                        </div>
                        
                        @if(isset($budgetRecommendation) && $budgetRecommendation)
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                                <p style="margin: 8px 0; font-size: 13px; color: #607d8b;">
                                    <strong>Budget Recommendation:</strong>
                                </p>
                                <p style="margin: 8px 0; font-size: 12px;">
                                    <span style="
                                        display: inline-block;
                                        padding: 4px 8px;
                                        border-radius: 4px;
                                        font-weight: 600;
                                        @if($budgetRecommendation['status'] === 'increase')
                                            background: #fef2f2;
                                            color: #dc2626;
                                        @elseif($budgetRecommendation['status'] === 'decrease')
                                            background: #f0fdf4;
                                            color: #16a34a;
                                        @else
                                            background: #f0f9ff;
                                            color: #0369a1;
                                        @endif
                                    ">
                                        {{ ucfirst($budgetRecommendation['status']) }}
                                    </span>
                                </p>
                                <p style="margin: 8px 0; font-size: 12px; color: #475569;">
                                    {{ $budgetRecommendation['message'] ?? '' }}
                                </p>
                            </div>
                        @endif
                    </div>

                    
                    <div class="dashboard-card">
                        <h3>Fuel Consumption</h3>
                        <p><strong>Monthly Limit:</strong> {{ $monthlyLimit }} liters</p>
                        <p><strong>Liters Used This Month:</strong> {{ $monthlyLitersUsed }} liters</p>

                        @php
                            $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;
                            if ($fuelPercent > 100) $fuelPercent = 100;
                        @endphp
                        <div class="fuel-bar">
                            <div class="fuel-used {{ $monthlyLitersUsed > $monthlyLimit ? 'warning' : '' }}" style="width: {{ $fuelPercent }}%;"></div>
                        </div>

                        @if($monthlyLitersUsed > $monthlyLimit)
                            <p class="warning-text"><strong>Warning:</strong> Exceeded monthly fuel limit!</p>
                        @endif
                    </div>

                    
                    <div class="dashboard-card">
                        <h3>Maintenance Overview</h3>
                        @if(isset($maintenanceOverview) && $maintenanceOverview->count() > 0)
                            <div style="margin: 10px 0 12px;">
                                <a class="btn-primary" href="{{ route('maintenances.index') }}">View All Maintenances</a>
                            </div>
                    
                        @else
                            <p style="color:#607d8b;">No maintenance records yet.</p>
                        @endif
                    </div>

                </div> 

            @else
                <p class="warning-text">No vehicle assigned.</p>
            @endif

        </div> 
    </div> 
</div>
@endsection
