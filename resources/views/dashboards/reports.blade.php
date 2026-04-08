@extends('layouts.app')

@section('title', 'Reports & Analytics')

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
            <a href="{{ route('admin.reports') }}" class="active"><svg viewBox="0 0 24 24"><path d="M9 17v-2H4.5A2.5 2.5 0 012 12.5v-9A2.5 2.5 0 014.5 1h9A2.5 2.5 0 0116 3.5V9h-2V3.5a.5.5 0 00-.5-.5h-9a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H9z"/><path d="M19 23h-9a2.5 2.5 0 01-2.5-2.5v-9a2.5 2.5 0 012.5-2.5h9a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5zM10 11a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h9a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5h-9z"/><circle cx="14.5" cy="17.5" r="1.5"/></svg>Reports</a>

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

        <div class="dashboard-container" style="overflow-y: auto; max-height: calc(100vh - 80px);">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2>Reports & Analytics</h2>
                    <p class="sub-text">Boardmember Comparison & Monthly Analysis - {{ $periodLabel }}</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-primary btn-sm">← Back to Dashboard</a>
            </div>

            <!-- Report Controls -->
            <div class="report-controls" style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                <form method="GET" action="{{ route('admin.reports') }}">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <!-- Report Type -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Report Type</label>
                            <select name="report_type" id="report-type" onchange="toggleMonthRange()" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; background: #ffffff; font-size: 14px; cursor: pointer;">
                                <option value="current-month" {{ $reportType == 'current-month' ? 'selected' : '' }}>Current Month</option>
                                <option value="quarterly" {{ $reportType == 'quarterly' ? 'selected' : '' }}>Quarterly (3 Months)</option>
                                <option value="semester" {{ $reportType == 'semester' ? 'selected' : '' }}>Semester (6 Months)</option>
                                <option value="custom-range" {{ $reportType == 'custom-range' ? 'selected' : '' }}>Custom Range</option>
                            </select>
                        </div>

                        <!-- Year Selection -->
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Year</label>
                            <select name="year" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; background: #ffffff; font-size: 14px; cursor: pointer;">
                                @for($y = now()->year; $y >= now()->year - 2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Month Range (for custom range) -->
                        <div id="month-range-container" style="{{ $reportType == 'custom-range' ? '' : 'display: none;' }}">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b; font-size: 14px;">Month Range</label>
                            <select name="month_range" style="width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; background: #ffffff; font-size: 14px; cursor: pointer;">
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

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button type="submit" class="btn-primary" style="padding: 10px 24px; font-size: 14px; font-weight: 600;">
                            Generate Report
                        </button>
                    </div>
                </form>

                <!-- Summary Stats -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                    <div style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">Total Boardmembers</div>
                        <div style="font-size: 24px; font-weight: 700;">{{ count($boardmemberStats) }}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">Total Fuel Cost</div>
                        <div style="font-size: 24px; font-weight: 700;">₱{{ number_format(collect($boardmemberStats)->sum('fuelSlipCost'), 0) }}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">Total Maintenance</div>
                        <div style="font-size: 24px; font-weight: 700;">₱{{ number_format(collect($boardmemberStats)->sum('maintenanceCost'), 0) }}</div>
                    </div>
                    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; padding: 16px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 12px; opacity: 0.9; margin-bottom: 4px;">Grand Total</div>
                        <div style="font-size: 24px; font-weight: 700;">₱{{ number_format(collect($boardmemberStats)->sum('fuelSlipCost') + collect($boardmemberStats)->sum('maintenanceCost'), 0) }}</div>
                    </div>
                </div>
            </div>

            <!-- Comparison Table -->
            <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; max-height: 500px; overflow-y: auto;">
                <style>
                    .table-wrapper::-webkit-scrollbar {
                        width: 8px;
                    }
                    .table-wrapper::-webkit-scrollbar-track {
                        background: #f1f5f9;
                        border-radius: 4px;
                    }
                    .table-wrapper::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 4px;
                    }
                    .table-wrapper::-webkit-scrollbar-thumb:hover {
                        background: #94a3b8;
                    }
                </style>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white;">
                            <th style="padding: 14px 16px; text-align: left; font-weight: 600; font-size: 13px;">Rank</th>
                            <th style="padding: 14px 16px; text-align: left; font-weight: 600; font-size: 13px;">Boardmember</th>
                            <th style="padding: 14px 16px; text-align: left; font-weight: 600; font-size: 13px;">Office</th>
                            <th style="padding: 14px 16px; text-align: right; font-weight: 600; font-size: 13px;">Fuel Cost</th>
                            <th style="padding: 14px 16px; text-align: right; font-weight: 600; font-size: 13px;">Maintenance</th>
                            <th style="padding: 14px 16px; text-align: right; font-weight: 600; font-size: 13px;">Total</th>
                            <th style="padding: 14px 16px; text-align: center; font-weight: 600; font-size: 13px;">% of Total</th>
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
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 12px 16px; font-weight: 700; color: #1e40af;">#{{ $rank++ }}</td>
                                <td style="padding: 12px 16px; font-weight: 600; color: #1e293b;">{{ $stats['name'] }}</td>
                                <td style="padding: 12px 16px; color: #64748b; font-size: 13px;">{{ $stats['office'] ?? 'N/A' }}</td>
                                <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: #d97706;">₱{{ number_format($stats['fuelSlipCost'] ?? 0, 2) }}</td>
                                <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: #059669;">₱{{ number_format($stats['maintenanceCost'] ?? 0, 2) }}</td>
                                <td style="padding: 12px 16px; text-align: right; font-weight: 700; color: #1e293b;">₱{{ number_format($total, 2) }}</td>
                                <td style="padding: 12px 16px; text-align: center;">
                                    <div style="background: #e0e7ff; border-radius: 20px; padding: 4px 12px; font-size: 12px; font-weight: 600; color: #4338ca;">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 24px; text-align: center; color: #64748b;">
                                    No data available for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<footer class="dashboard-footer">
    <span>&copy; Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union <span class="footer-divider">|</span> Janial Bacani
</footer>

<script>
    function toggleMonthRange() {
        const reportType = document.getElementById('report-type').value;
        const monthRangeContainer = document.getElementById('month-range-container');
        
        if (reportType === 'custom-range') {
            monthRangeContainer.style.display = 'block';
        } else {
            monthRangeContainer.style.display = 'none';
        }
    }
</script>

@endsection
