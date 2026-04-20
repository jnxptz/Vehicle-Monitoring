@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-dashboard-styles.css') }}">
<div class="dashboard-page">
    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>
        {{-- Hamburger Menu (Mobile Only) --}}
        <div class="hamburger-menu-wrapper">
            <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
            <label for="hamburger-toggle" class="hamburger-btn">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <nav class="hamburger-dropdown">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>

                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>

                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>

                <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>

                <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>

                <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>

                <div class="logout-form">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </nav>
        </div>
    </div>

    <div class="dashboard-body">

        <!-- SIDEBAR -->
        <nav class="dashboard-nav">
            @include('partials.sidebar-profile')
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            
            <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M9 17v-2H4.5A2.5 2.5 0 012 12.5v-9A2.5 2.5 0 014.5 1h9A2.5 2.5 0 0116 3.5V9h-2V3.5a.5.5 0 00-.5-.5h-9a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H9z"/><path d="M19 23h-9a2.5 2.5 0 01-2.5-2.5v-9a2.5 2.5 0 012.5-2.5h9a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5zM10 11a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h9a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5h-9z"/><circle cx="14.5" cy="17.5" r="1.5"/></svg>Reports</a>
            
            <div class="bottom-section">
                <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
            </div>
            <div class="logout-form">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>

                </form>
            </div>
        </nav>
        <div class="dashboard-container">
            <!-- PAGE HEADER -->
            <div class="page-header" style="margin-bottom: 5px;">
                <div>
                    <h2>Boardmembers Overview</h2>
                
                </div>
            </div>

            <!-- CONTROLS & KPI -->
            <div class="report-controls" style="background: #ffffff; border-radius: 12px; padding: 16px; margin-bottom: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);">
                <!-- Filters & Export -->
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9; margin-bottom: 16px;">
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="flex-grow: 1; display: flex; gap: 12px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px; max-width: 300px;">
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Office / Department</label>
                            <select name="office" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc; color: #1e293b; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http://www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%2010l5%205%205%205z%22%20fill%3D%22%236b7280%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 18px;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                <option value="">All Offices</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div style="flex: 1; min-width: 160px; max-width: 240px;">
                            <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Month</label>
                            <select name="month" onchange="this.form.submit()" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; background: #f8fafc; color: #1e293b; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http://www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%2010l5%205%205%205z%22%20fill%3D%22%236b7280%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 12px center; background-size: 18px;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                @foreach(range(1,12) as $month)
                                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a href="{{ route('admin.dashboard.monthly.pdf', ['month' => $selectedMonth, 'office' => $selectedOffice, 'year' => $year]) }}" class="export-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(59, 130, 246, 0.2)';">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Export Monthly PDF
                        </a>

                        <a href="{{ route('admin.dashboard.yearly.pdf') }}" class="export-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2); text-decoration: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(16, 185, 129, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(16, 185, 129, 0.2)';">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Export Yearly PDF
                        </a>
                    </div>
                </div>

                <!-- KPI -->
                <div class="kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div class="kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)';">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #3b82f6, #60a5fa);"></div>
                        <h4 style="margin: 0 0 6px 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Budget</h4>
                        <p style="margin: 0; font-size: 24px; font-weight: 700; color: #1e293b;">₱{{ number_format($rows->sum('yearlyBudget'), 2, '.', ',') }}</p>
                    </div>

                    <div class="kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)';">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #f59e0b, #fbbf24);"></div>
                        <h4 style="margin: 0 0 6px 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Used</h4>
                        <p style="margin: 0; font-size: 24px; font-weight: 700; color: #dc2626;">₱{{ number_format($rows->sum('totalUsed'), 2, '.', ',') }}</p>
                    </div>

                    <div class="kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)';">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #10b981, #34d399);"></div>
                        <h4 style="margin: 0 0 6px 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Remaining Budget</h4>
                        <p style="margin: 0; font-size: 24px; font-weight: 700; color: #059669;">₱{{ number_format($rows->sum('remainingBudget'), 2, '.', ',') }}</p>
                    </div>

                    <div class="kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.02)';">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #8b5cf6, #a78bfa);"></div>
                        <h4 style="margin: 0 0 6px 0; font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Liters ({{ $selectedMonthName }})</h4>
                        <p style="margin: 0; font-size: 24px; font-weight: 700; color: #4338ca;">{{ number_format($rows->sum('monthlyLitersUsed'), 2, '.', ',') }} L</p>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; max-height: 600px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; margin: 0 !important; flex: 1;">
                <style>
                    .table-wrapper::-webkit-scrollbar {
                        display: none;
                    }
                </style>
                <table class="modern-table" style="width: 100%; border-collapse: collapse; border: none;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">#</th>
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Boardmember</th>
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Budget Usage</th>
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Remaining</th>
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">{{ $selectedMonthName }} Liters</th>
                            <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $index => $row)
                            @php
                                $percent = $row['yearlyBudget'] > 0
                                    ? ($row['totalUsed'] / $row['yearlyBudget']) * 100
                                    : 0;
                                // Check if user has exceeded fuel limit
                                $hasExceededFuelLimit = false;
                                if(isset($row['vehicles']) && count($row['vehicles']) > 0) {
                                    foreach($row['vehicles'] as $vehicle) {
                                        $vehicleData = $vehicle['vehicle'];
                                        if(isset($vehicleData->monthly_fuel_limit) && $vehicleData->monthly_fuel_limit > 0) {
                                            // Get monthly fuel usage for this vehicle for the selected month
                                            $monthlyVehicleFuelSlips = \App\Models\FuelSlip::where('user_id', $row['user']->id)
                                                ->where('vehicle_id', $vehicleData->id)
                                                ->whereMonth('date', $selectedMonth)
                                                ->whereYear('date', $year)
                                                ->get();
                                            $monthlyVehicleLitersUsed = $monthlyVehicleFuelSlips->sum('liters');
                                            if($monthlyVehicleLitersUsed > $vehicleData->monthly_fuel_limit) {
                                                $hasExceededFuelLimit = true;
                                                break;
                                            }
                                        }
                                    }
                                }
                                $status = 'Normal';
                                $statusClass = 'status-green';
                                if ($hasExceededFuelLimit) {
                                    $status = 'Exceeded Fuel Limit';
                                    $statusClass = 'status-red';
                                } elseif ($percent >= 80) {
                                    $status = 'Critical';
                                    $statusClass = 'status-red';
                                } elseif ($percent >= 50) {
                                    $status = 'Warning';
                                    $statusClass = 'status-yellow';
                                }
                                $rowId = 'row-' . $index;
                            @endphp

                            <!-- MAIN ROW -->
                            <tr class="clickable-row" style="background: {{ $index % 2 == 0 ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease; pointer-events: none;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $index % 2 == 0 ? '#f8fafc' : '#ffffff' }}';">
                                <td data-label="#" style="padding: 16px 20px; border: none; font-weight: 500; color: #1e40af;">{{ $index + 1 }}</td>
                                <td data-label="Boardmember" style="padding: 16px 20px; border: none;">
                                    <div>
                                        <div class="name" style="font-weight: 500; color: #1e293b;">{{ $row['user']->name }}</div>
                                        <div class="email" style="font-size: 13px; color: #64748b;">{{ $row['user']->email }}</div>
                                    </div>
                                </td>
                                <td data-label="Budget Usage" style="padding: 16px 20px; border: none;">
                                    <div class="progress-wrapper">
                                        <div class="progress-bar" style="background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden;">
                                            <div class="progress-fill" style="width: {{ min($percent,100) }}%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); height: 100%; border-radius: 10px;"></div>
                                        </div>
                                        <small style="color: #64748b; font-size: 12px;">
                                            ₱{{ number_format($row['totalUsed'],2) }} / ₱{{ number_format($row['yearlyBudget'],2) }} ({{ number_format($percent,1) }}%)

                                    </div>
                                </td>
                                <td data-label="Remaining" style="padding: 16px 20px; border: none; font-weight: 500; color: #059669;">₱{{ number_format($row['remainingBudget'],2) }}</td>
                                <td data-label="{{ $selectedMonthName }} Liters" style="padding: 16px 20px; border: none; color: #1e293b;">{{ number_format($row['monthlyLitersUsed'],2) }} L</td>

                                <td data-label="Status" style="padding: 16px 20px; border: none;">
                                    <span class="status-badge {{ $statusClass }}" style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block;">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                            <!-- VEHICLE DETAILS -->
                            @if(count($row['vehicles']) > 0)
                                <tr id="{{ $rowId }}-details" class="details-row">
                                    <td colspan="6">
                                        <div class="vehicle-cards">
                                            @foreach($row['vehicles'] as $v)
                                                <div class="vehicle-card">
                                                    <div class="vehicle-main">
                                                        <div class="vehicle-name">
                                                            {{ $v['vehicle']->vehicle_name }}
                                                            <span class="vehicle-plate">
                                                                {{ $v['vehicle']->plate_number }}
                                                            </span>
                                                        </div>
                                                        <div class="vehicle-limit">
                                                            Limit: {{ $v['vehicle']->monthly_fuel_limit }} L
                                                        </div>
                                                    </div>
                                                    <div class="vehicle-stats">
                                                        <div class="stat">
                                                            <div class="label">Fuel</div>
                                                            <div class="value">
                                                                ₱{{ number_format($v['fuelSlipCost'],2) }}
                                                            </div>
                                                        </div>
                                                        <div class="stat">
                                                            <div class="label">Maintenance</div>
                                                            <div class="value">
                                                                ₱{{ number_format($v['maintenanceCost'],2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="dashboard-footer">
    <span>&copy; Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union <span class="footer-divider">|</span>J.M.B
</footer>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endsection
