@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

<div class="dashboard-page">

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
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
                <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>
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

        <!-- SIDEBAR -->
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>

            <div style="margin-top:auto;border-top:1px solid #e2e8f0;padding-top:12px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
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

                <form method="GET" action="{{ route('admin.dashboard') }}" class="filter-bar" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <select name="office" onchange="this.form.submit()" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; color: #1e293b; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="month" onchange="this.form.submit()" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; color: #1e293b; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                        @foreach(range(1,12) as $month)
                            <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>

                    <a href="{{ route('admin.dashboard.monthly.pdf', ['month' => $selectedMonth, 'office' => $selectedOffice, 'year' => $year]) }}" class="export-btn btn-primary" style="padding: 10px 18px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1e40af 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(59, 130, 246, 0.2)';">
                        Export Monthly PDF
                    </a>

                    <a href="{{ route('admin.dashboard.yearly.pdf') }}" class="export-btn btn-primary" style="padding: 10px 18px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #10b981 0%, #059669 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(16, 185, 129, 0.2)';">
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
                        <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
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

                                $status = 'Normal';
                                $statusClass = 'status-green';

                                if ($percent >= 80) {
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

function toggleRow(rowId) {
    const detailsRow = document.getElementById(rowId + '-details');
    if (!detailsRow) return;

    const isVisible = detailsRow.style.display === 'table-row';
    detailsRow.style.display = isVisible ? 'none' : 'table-row';
}

function toggleIcon(btn) {
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
}
</script>

@endsection
