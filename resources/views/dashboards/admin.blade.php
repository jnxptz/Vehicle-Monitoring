@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>

        
    </div>

    <div class="dashboard-body">

        <!-- SIDEBAR -->
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('offices.index') }}">Offices</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}">Manage Boardmembers</a>

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

                    <a href="{{ route('admin.dashboard.yearly.pdf') }}" class="export-btn btn-primary">
                        Export Yearly PDF
                    </a>
                </form>
            </div>

            <!-- KPI -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <h4>Total Budget</h4>
                    <p>₱{{ number_format($rows->sum('yearlyBudget'),2) }}</p>
                </div>

                <div class="kpi-card">
                    <h4>Total Used</h4>
                    <p>₱{{ number_format($rows->sum('totalUsed'),2) }}</p>
                </div>

                <div class="kpi-card">
                    <h4>Remaining Budget</h4>
                    <p>₱{{ number_format($rows->sum('remainingBudget'),2) }}</p>
                </div>

                <div class="kpi-card">
                    <h4>Total Liters ({{ $selectedMonthName }})</h4>
                    <p>{{ number_format($rows->sum('monthlyLitersUsed'),2) }} L</p>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Boardmember</th>
                            <th>Budget Usage</th>
                            <th>Remaining</th>
                            <th>{{ $selectedMonthName }} Liters</th>
                            <th>Status</th>
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
                            <tr onclick="toggleRow('{{ $rowId }}')" class="clickable-row">
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <div class="name">{{ $row['user']->name }}</div>
                                    <div class="email">{{ $row['user']->email }}</div>
                                </td>

                                <td>
                                    <div class="progress-wrapper">
                                        <div class="progress-bar">
                                            <div class="progress-fill"
                                                style="width: {{ min($percent,100) }}%">
                                            </div>
                                        </div>
                                        <small>
                                            ₱{{ number_format($row['totalUsed'],2) }} /
                                            ₱{{ number_format($row['yearlyBudget'],2) }}
                                            ({{ number_format($percent,1) }}%)
                                        </small>
                                    </div>
                                </td>

                                <td>₱{{ number_format($row['remainingBudget'],2) }}</td>
                                <td>{{ number_format($row['monthlyLitersUsed'],2) }} L</td>

                                <td>
                                    <span class="status-badge {{ $statusClass }}">
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
                                                            <div class="label">Fuel YTD</div>
                                                            <div class="value">
                                                                ₱{{ number_format($v['fuelSlipCost'],2) }}
                                                            </div>
                                                        </div>

                                                        <div class="stat">
                                                            <div class="label">Maintenance YTD</div>
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

<!-- STYLE -->
<style>
.details-row { display:none;background:#f8fafc; }
.details-row td { padding:20px !important;border:none !important; }

.vehicle-cards {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:16px;
}

.vehicle-card {
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:16px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
    transition:0.2s;
}

.vehicle-card:hover { transform:translateY(-3px); }

.vehicle-name { font-weight:600;color:#111827; }
.vehicle-plate { font-size:13px;color:#6b7280;margin-left:6px; }
.vehicle-limit { font-size:12px;color:#475569;margin-top:4px; }

.vehicle-stats { display:flex;gap:10px;margin-top:12px; }

.stat {
    flex:1;
    background:#f1f5f9;
    padding:10px;
    border-radius:8px;
    text-align:center;
}

.stat .label { font-size:11px;color:#64748b; }
.stat .value { font-weight:600;color:#0f172a; }
</style>

<script>
function toggleRow(rowId) {
    const detailsRow = document.getElementById(rowId + '-details');
    if (!detailsRow) return;

    const isVisible = detailsRow.style.display === 'table-row';
    detailsRow.style.display = isVisible ? 'none' : 'table-row';
}
</script>

@endsection
