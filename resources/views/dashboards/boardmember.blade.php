@extends('layouts.app')



@section('content')

<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">



@php

use Carbon\Carbon;

@endphp



<style>

    /* Mobile specific styles */

    @media (max-width: 768px) {

        .dashboard-header {

            display: flex;

            justify-content: flex-start;

            align-items: center;

            padding: 0 16px;

            width: 100%;

        }



        .hamburger-menu-wrapper {

            margin-left: auto;

            order: 2;

            flex-shrink: 0;

            display: flex;

            align-items: center;

        }



        .dashboard-title {

            order: 1;

            flex-shrink: 0;

            display: flex;

            align-items: center;

        }



        .dashboard-title img {

            height: 40px;

            width: auto;

            margin-right: 8px;

            flex-shrink: 0;

        }



        .dashboard-title h1 {

            font-size: 18px;

            margin: 0;

            white-space: nowrap;

        }

    }

</style>



<div class="dashboard-page">



    

    <div class="dashboard-header">

        <div class="dashboard-title">

            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">

            <h1>Sangguniang Panlalawigan</h1>

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

                <div style="border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 8px;">

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



            <div style="margin-top:auto;border-top:1px solid #e2e8f0;padding-top:12px;">

                <form action="{{ route('logout') }}" method="POST">

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

                    <form method="GET" action="{{ route('boardmember.dashboard') }}" class="filter-bar" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">

                        <select name="month" onchange="this.form.submit()" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; color: #1e293b; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">

                            @foreach(range(1, 12) as $month)

                                <option value="{{ $month }}" {{ isset($selectedMonth) && $month == $selectedMonth ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}

                                </option>

                            @endforeach

                        </select>



                        <a href="{{ route('boardmember.dashboard.pdf', ['month' => $selectedMonth ?? now()->month]) }}" class="export-btn btn-primary" style="padding: 10px 18px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1e40af 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.2)';">

                            Export Monthly PDF

                        </a>



                        <a href="{{ route('boardmember.dashboard.yearly.pdf') }}" class="export-btn btn-primary" style="padding: 10px 18px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #10b981 0%, #059669 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.2)';">

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

                <div class="alerts-box" style="margin-bottom: 20px; padding: 16px 20px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 1px solid #f59e0b; border-radius: 12px; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);">

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">

                        <div style="font-size: 20px;">⚠️</div>

                        <h4 style="margin: 0; font-size: 16px; color: #92400e; font-weight: 600;">Alerts</h4>

                    </div>

                    <ul style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.6;">

                        @foreach($alerts as $alert)

                            <li>{{ $alert }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- Vehicle list (boardmember may have multiple vehicles) --}}

            @if(isset($vehicles) && $vehicles->count() > 0)

                <div class="vehicle-list" style="display:flex; gap:12px; margin: 16px 0 24px 0; flex-wrap:wrap;">

                    @foreach($vehicles as $v)

                        <div class="vehicle-pill" style="padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.04); transition: all 0.2s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.04)';">

                            <strong style="display:block; color: #1e293b; font-size: 15px; margin-bottom: 4px;">{{ $v->plate_number }}</strong>

                            <small style="color: #64748b; font-size: 12px;">{{ $v->make ?? '' }} {{ $v->model ?? '' }}</small>

                        </div>

                    @endforeach

                </div>

            @endif



            {{-- KPI CARDS --}}

            @if(isset($vehicle) && $vehicle)

                <div class="kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">

                    <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">

                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Yearly Budget</h4>

                        <p style="margin: 0; font-size: 24px; font-weight: 600; color: #1e293b;">₱{{ number_format($yearlyBudget, 2) }}</p>

                    </div>



                    <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">

                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Budget Used</h4>

                        <p style="margin: 0; font-size: 24px; font-weight: 600; color: #dc2626;">₱{{ number_format($yearlyBudget - $remainingBudget, 2) }}</p>

                    </div>



                    <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">

                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Remaining Budget</h4>

                        <p style="margin: 0; font-size: 24px; font-weight: 600; color: #059669;">₱{{ number_format($remainingBudget, 2) }}</p>

                    </div>



                    <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">

                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Fuel Used ({{ $selectedMonthName ?? \Carbon\Carbon::now()->format('F') }})</h4>

                        <p style="margin: 0; font-size: 24px; font-weight: 600; color: #1d4ed8;">{{ $monthlyLitersUsed }} L</p>

                    </div>

                </div>



                {{-- OVERVIEW CARDS --}}

                <div class="dashboard-sections" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 24px;">

                    

                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.04)';">

                        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #1e293b; font-weight: 600;">Budget Progress</h3>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">

                            <span style="color: #64748b; font-size: 14px;">Used</span>

                            <span style="color: #1e293b; font-size: 18px; font-weight: 600;">{{ $budgetUsedPercentage }}%</span>

                        </div>

                        <div class="budget-bar" style="background: #e2e8f0; border-radius: 12px; height: 12px; overflow: hidden;">

                            <div class="budget-used {{ $budgetUsedPercentage >= 80 ? 'warning' : '' }}" style="width: {{ $budgetUsedPercentage }}%; background: {{ $budgetUsedPercentage >= 80 ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' : 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)' }}; height: 100%; border-radius: 12px; transition: width 0.3s ease;"></div>

                        </div>

                        <p style="margin: 12px 0 0 0; font-size: 13px; color: #64748b;">₱{{ number_format($yearlyBudget - $remainingBudget, 2) }} used of ₱{{ number_format($yearlyBudget, 2) }}</p>

                    </div>



                    

                    <div class="dashboard-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.04); transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.04)';">

                        <h3 style="margin: 0 0 16px 0; font-size: 18px; color: #1e293b; font-weight: 600;">Fuel Consumption</h3>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">

                            <span style="color: #64748b; font-size: 14px;">{{ $monthlyLitersUsed }} L / {{ $monthlyLimit }} L</span>

                            @php

                                $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;

                                if ($fuelPercent > 100) $fuelPercent = 100;

                            @endphp

                            <span style="color: {{ $monthlyLitersUsed > $monthlyLimit ? '#dc2626' : '#1d4ed8' }}; font-size: 18px; font-weight: 600;">{{ $fuelPercent }}%</span>

                        </div>

                        <div class="fuel-bar" style="background: #e2e8f0; border-radius: 12px; height: 12px; overflow: hidden;">

                            <div class="fuel-used {{ $monthlyLitersUsed > $monthlyLimit ? 'warning' : '' }}" style="width: {{ $fuelPercent }}%; background: {{ $monthlyLitersUsed > $monthlyLimit ? 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)' : 'linear-gradient(135deg, #10b981 0%, #059669 100%)' }}; height: 100%; border-radius: 12px; transition: width 0.3s ease;"></div>

                        </div>

                        @if($monthlyLitersUsed > $monthlyLimit)

                            <p class="warning-text" style="margin-top: 12px; padding: 8px 12px; background: #fee2e2; color: #dc2626; border-radius: 8px; font-size: 13px; font-weight: 500;"><strong>⚠ Warning:</strong> Exceeded fuel limit!</p>

                        @endif

                    </div>

                </div>

            @else

                <div style="padding: 48px 32px; text-align: center; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-radius: 16px; border: 2px dashed #e2e8f0;">

                    <div style="font-size: 48px; margin-bottom: 16px;">🚗</div>

                    <p style="font-size: 18px; color: #64748b; font-weight: 500; margin: 0 0 8px 0;">No vehicle assigned</p>

                    <p style="font-size: 14px; color: #94a3b8; margin: 0;">Please contact your administrator to assign a vehicle to your account.</p>

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

