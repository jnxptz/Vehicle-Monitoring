@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-dashboard-styles.css') }}">
<style>
    /* Fixed Header and Sidebar Layout */
    .dashboard-header {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1100 !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px);
        height: 70px;
        padding: 10px 20px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-body {
        margin-top: 70px; /* Offset for fixed header */
        display: flex;
        height: calc(100vh - 70px);
        overflow: hidden;
        padding: 0 !important;
        gap: 0 !important;
    }

    .dashboard-nav {
        position: fixed !important;
        top: 70px;
        left: 0;
        width: 240px;
        height: calc(100vh - 70px) !important;
        overflow-y: auto;
        z-index: 1000;
        border-radius: 0 !important;
        margin: 0 !important;
        border-right: 1px solid #e2e8f0;
        flex: none !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .dashboard-container {
        margin-left: 240px; /* Offset for fixed sidebar */
        display: flex !important;
        flex-direction: column !important;
        flex: 1;
        overflow-y: auto !important;
        height: calc(100vh - 70px);
        padding: 24px !important;
        border: none !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        scrollbar-width: thin;
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
</style>

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
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('vehicles.index') }}">Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}">Maintenances</a>
                <a href="{{ route('admin.reports') }}" class="active">Reports</a>
                <a href="{{ route('offices.index') }}">Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}">Manage Users</a>
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

            <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            <a href="{{ route('admin.reports') }}" class="active"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Reports</a>

            <div class="bottom-section">
                <a href="{{ route('offices.index') }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
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
            <div class="page-header" style="margin-bottom: 20px;">
                <div>
                    <h2>Reports & Analytics</h2>
                    
                </div>
                
            </div>

            <!-- Report Controls -->
            <div class="report-controls" style="background: #f8fafc; border-radius: 12px; padding: 12px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px;">
                    <form method="GET" action="{{ route('admin.reports') }}" style="flex-grow: 1;">
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <!-- Report Type -->
                            <div style="flex: 1; min-width: 160px;">
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Report Period</label>
                                <select name="report_type" id="report-type" onchange="toggleMonthRange(); this.form.submit();" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                                    <option value="current-month" {{ $reportType == 'current-month' ? 'selected' : '' }}>This Month</option>
                                    <option value="single-month" {{ $reportType == 'single-month' ? 'selected' : '' }}>Specific Month</option>
                                    <option value="quarterly" {{ $reportType == 'quarterly' ? 'selected' : '' }}>Quarterly (3 Months)</option>
                                    <option value="semester" {{ $reportType == 'semester' ? 'selected' : '' }}>Semester (6 Months)</option>
                                    <option value="custom-range" {{ $reportType == 'custom-range' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Single Month Selection -->
                            <div id="single-month-container" style="flex: 1; min-width: 120px; {{ $reportType == 'single-month' ? '' : 'display: none;' }}">
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Select Month</label>
                                <select name="month_range" id="single-month-select" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ ($reportType == 'single-month' && $monthRange == $m) || ($reportType != 'single-month' && now()->month == $m) ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Year Selection -->
                            <div style="flex: 1; min-width: 120px;">
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Year</label>
                                <select name="year" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Month Range (for custom range) -->
                            <div id="month-range-container" style="flex: 1; min-width: 160px; {{ $reportType == 'custom-range' ? '' : 'display: none;' }}">
                                <label style="display: block; font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Range Period</label>
                                <select name="month_range" id="month-range-select" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #ffffff; font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                                    <option value="" disabled {{ !$monthRange ? 'selected' : '' }}>-- Select Range --</option>
                                    @foreach([
                                        '1-2' => 'January - February',
                                        '2-3' => 'February - March',
                                        '3-4' => 'March - April',
                                        '4-5' => 'April - May',
                                        '5-6' => 'May - June',
                                        '6-7' => 'June - July',
                                        '7-8' => 'July - August',
                                        '8-9' => 'August - September',
                                        '9-10' => 'September - October',
                                        '10-11' => 'October - November',
                                        '11-12' => 'November - December',
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ $monthRange == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.reports.pdf', ['year' => $year, 'report_type' => $reportType, 'month_range' => $monthRange]) }}" class="export-btn btn-primary" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #ffffff; background: #2563eb; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(37,99,235,0.2); text-decoration: none;" onmouseover="this.style.background='#1d4ed8';" onmouseout="this.style.background='#2563eb';">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Export PDF
                        </a>
                    </div>
                </div>

                <!-- Summary Stats -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 12px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-top: 4px solid #3b82f6;">
                        <h4 style="margin: 0 0 8px 0; font-size: 12px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Boardmembers</h4>
                        <p style="margin: 0; font-size: 28px; font-weight: 700; color: #1e293b;">{{ count($boardmemberStats) }}</p>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-top: 4px solid #f59e0b;">
                        <h4 style="margin: 0 0 8px 0; font-size: 12px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Fuel Cost</h4>
                        <p style="margin: 0; font-size: 28px; font-weight: 700; color: #dc2626;">₱{{ number_format(collect($boardmemberStats)->sum('fuelSlipCost'), 0) }}</p>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-top: 4px solid #10b981;">
                        <h4 style="margin: 0 0 8px 0; font-size: 12px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Maintenance</h4>
                        <p style="margin: 0; font-size: 28px; font-weight: 700; color: #059669;">₱{{ number_format(collect($boardmemberStats)->sum('maintenanceCost'), 0) }}</p>
                    </div>
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-top: 4px solid #8b5cf6;">
                        <h4 style="margin: 0 0 8px 0; font-size: 12px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Grand Total</h4>
                        <p style="margin: 0; font-size: 28px; font-weight: 700; color: #1d4ed8;">₱{{ number_format(collect($boardmemberStats)->sum('fuelSlipCost') + collect($boardmemberStats)->sum('maintenanceCost'), 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; max-height: 600px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; margin: 0 !important; flex: 1;">
                <style>
                    .table-wrapper::-webkit-scrollbar {
                        display: none;
                    }
                </style>
                <table class="modern-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                            <th style="padding: 16px 20px; text-align: left; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">#</th>
                            <th style="padding: 16px 20px; text-align: left; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">Boardmember</th>
                            <th style="padding: 16px 20px; text-align: left; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">Office</th>
                            <th style="padding: 16px 20px; text-align: right; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">Fuel Cost</th>
                            <th style="padding: 16px 20px; text-align: right; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">Maintenance</th>
                            <th style="padding: 16px 20px; text-align: right; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border: none;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sortedStats = collect($boardmemberStats)->sortByDesc(function($item) {
                                return ($item['fuelSlipCost'] ?? 0) + ($item['maintenanceCost'] ?? 0);
                            });
                            $grandTotal = $sortedStats->sum('fuelSlipCost') + $sortedStats->sum('maintenanceCost');
                            $rank = 1;
                        @endphp
                        @forelse($sortedStats as $id => $stats)
                            @php
                                $total = ($stats['fuelSlipCost'] ?? 0) + ($stats['maintenanceCost'] ?? 0);
                                $percentage = $grandTotal > 0 ? ($total / $grandTotal) * 100 : 0;
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.15s ease;">
                                <td style="padding: 14px 20px; font-weight: 700; color: #1e40af; font-size: 14px; border: none;">#{{ $rank++ }}</td>
                                <td style="padding: 14px 20px; font-weight: 600; color: #1e293b; font-size: 14px; border: none;">{{ $stats['name'] }}</td>
                                <td style="padding: 14px 20px; color: #64748b; font-size: 13px; border: none;">{{ $stats['office'] ?? 'N/A' }}</td>
                                <td style="padding: 14px 20px; text-align: right; font-weight: 600; color: #d97706; font-size: 14px; border: none;">₱{{ number_format($stats['fuelSlipCost'] ?? 0, 2) }}</td>
                                <td style="padding: 14px 20px; text-align: right; font-weight: 600; color: #059669; font-size: 14px; border: none;">₱{{ number_format($stats['maintenanceCost'] ?? 0, 2) }}</td>
                                <td style="padding: 14px 20px; text-align: right; font-weight: 700; color: #1e293b; font-size: 14px; border: none;">₱{{ number_format($total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 48px 24px; text-align: center; color: #94a3b8; border: none;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #cbd5e1;">
                                            <path d="M9 17v-2H4.5A2.5 2.5 0 012 12.5v-9A2.5 2.5 0 014.5 1h9A2.5 2.5 0 0116 3.5V9h-2V3.5a.5.5 0 00-.5-.5h-9a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H9z"/>
                                            <path d="M19 23h-9a2.5 2.5 0 01-2.5-2.5v-9a2.5 2.5 0 012.5-2.5h9a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5z"/>
                                        </svg>
                                        <span style="font-size: 14px; font-weight: 500;">No data available for the selected period</span>
                                        <span style="font-size: 12px; color: #cbd5e1;">Try adjusting your filters to see results</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    function toggleMonthRange() {
        const reportType = document.getElementById('report-type').value;
        const monthRangeContainer = document.getElementById('month-range-container');
        const singleMonthContainer = document.getElementById('single-month-container');
        
        if (reportType === 'custom-range') {
            monthRangeContainer.style.display = 'block';
            singleMonthContainer.style.display = 'none';
        } else if (reportType === 'single-month') {
            monthRangeContainer.style.display = 'none';
            singleMonthContainer.style.display = 'block';
        } else {
            monthRangeContainer.style.display = 'none';
            singleMonthContainer.style.display = 'none';
        }
    }
</script>

@endsection
