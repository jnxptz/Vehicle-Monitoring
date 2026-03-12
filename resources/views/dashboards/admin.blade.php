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
            <div class="page-header">
                <div>
                    <h2>Boardmembers Overview</h2>
                    <p class="sub-text">{{ $selectedMonthName }} {{ $year }}</p>
                </div>

                <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar">
                    <select name="office" onchange="this.form.submit()">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="month" onchange="this.form.submit()">
                        @foreach(range(1,12) as $month)
                            <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>

                    <a href="{{ route('admin.dashboard.monthly.pdf', ['month' => $selectedMonth, 'office' => $selectedOffice, 'year' => $year]) }}" class="export-btn btn-primary">
                        Export Monthly PDF
                    </a>

                    <a href="{{ route('admin.dashboard.yearly.pdf') }}" class="export-btn btn-primary">
                        Export Yearly PDF
                    </a>
                </form>
            </div>

            <!-- KPI -->
            <div class="kpi-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Budget</h4>
                    <p style="margin: 0; font-size: 24px; font-weight: 600; color: #1e293b;">₱{{ number_format($rows->sum('yearlyBudget'),2) }}</p>
                </div>

                <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Used</h4>
                    <p style="margin: 0; font-size: 24px; font-weight: 600; color: #dc2626;">₱{{ number_format($rows->sum('totalUsed'),2) }}</p>
                </div>

                <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Remaining Budget</h4>
                    <p style="margin: 0; font-size: 24px; font-weight: 600; color: #059669;">₱{{ number_format($rows->sum('remainingBudget'),2) }}</p>
                </div>

                <div class="kpi-card" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: transform 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Liters ({{ $selectedMonthName }})</h4>
                    <p style="margin: 0; font-size: 24px; font-weight: 600; color: #1d4ed8;">{{ number_format($rows->sum('monthlyLitersUsed'),2) }} L</p>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
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
                            <tr onclick="toggleRow('{{ $rowId }}')" class="clickable-row" style="background: {{ $index % 2 == 0 ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $index % 2 == 0 ? '#f8fafc' : '#ffffff' }}';">
                                <td data-label="#" style="padding: 16px 20px; border: none; font-weight: 500; color: #1e40af;">{{ $index + 1 }}</td>

                                <td data-label="Boardmember" style="padding: 16px 20px; border: none;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <button class="expand-btn" onclick="event.stopPropagation(); toggleRow('{{ $rowId }}'); toggleIcon(this);" aria-expanded="false" style="background: none; border: none; cursor: pointer; padding: 4px;">
                                            <svg class="chev" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 9l6 6 6-6" stroke="#1e40af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <div>
                                            <div class="name" style="font-weight: 500; color: #1e293b;">{{ $row['user']->name }}</div>
                                            <div class="email" style="font-size: 13px; color: #64748b;">{{ $row['user']->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Budget Usage" style="padding: 16px 20px; border: none;">
                                    <div class="progress-wrapper">
                                        <div class="progress-bar" style="background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden;">
                                            <div class="progress-fill" style="width: {{ min($percent,100) }}%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); height: 100%; border-radius: 10px;"></div>
                                        </div>
                                        <small style="color: #64748b; font-size: 12px;">
                                            ₱{{ number_format($row['totalUsed'],2) }} / ₱{{ number_format($row['yearlyBudget'],2) }} ({{ number_format($percent,1) }}%)
                                        </small>
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
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>

<script src="{{ asset('js/admin-dashboard.js') }}"></script>

@endsection
