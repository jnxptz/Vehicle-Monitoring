<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Yearly Dashboard PDF</title>
    <link rel="stylesheet" href="{{ public_path('css/boardmember_pdf.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px 28px;
            background: #fff;
            line-height: 1.4;
        }

        /* ── PAGE BREAK ── */
        .page-break { page-break-after: always; margin-top: 40px; }

        /* ── TITLE PAGE ── */
        .title-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: 750px;
            page-break-after: always;
        }
        .title-page-logo { margin-bottom: 30px; }
        .title-page-logo img { width: 80px; height: auto; }
        .title-page-heading { font-size: 32px; font-weight: 700; color: #0b2e66; margin: 20px 0; }
        .title-page-subtitle { font-size: 16px; color: #555; margin: 8px 0; }
        .title-page-date { font-size: 13px; color: #888; margin: 20px 0; font-style: italic; }
        .title-page-footer { position: absolute; bottom: 40px; width: 100%; text-align: center; font-size: 11px; color: #999; }

        /* ── HEADER ── */
        .header-table {
            width: 460px;
            border-collapse: collapse;
            margin: 0 auto 14px;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 10px;
        }
        .header-table td { vertical-align: middle; padding-bottom: 10px; }
        .header-logo { width: 56px; text-align: center; padding: 0 4px; }
        .header-logo img { width: 44px; height: auto; }
        .header-center { text-align: center; padding: 0 6px; }
        .gov-title  { font-size: 14px; font-weight: 700; color: #111; letter-spacing: 0.5px; }
        .sub-title  { font-size: 10.5px; color: #555; margin-top: 2px; }
        .doc-title  { font-size: 13px; font-weight: 600; color: #222; margin-top: 4px; }

        /* ── META ── */
        .meta-table {
            width: 100%; border-collapse: collapse; margin-bottom: 16px;
            background: #f0f4fb; border-radius: 4px;
        }
        .meta-table td { padding: 7px 12px; font-size: 10px; color: #444; width: 50%; }
        .label { font-weight: 600; color: #1976d2; }

        /* ── SECTION TITLES ── */
        h2 {
            font-size: 12px; font-weight: 700; color: #0b2e66;
            margin: 16px 0 10px; padding-bottom: 6px;
            border-bottom: 2px solid #1976d2;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        h3 { font-size: 11px; font-weight: 600; color: #1976d2; margin: 12px 0 8px; }

        /* ── SUMMARY CARDS ── */
        .cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 12px;
        }
        .card {
            width: 33.33%;
            background: #f7f9fd;
            border: 1px solid #d0d9e8;
            border-radius: 5px;
            padding: 12px 14px;
            text-align: center;
            vertical-align: middle;
        }
        .card-2col { width: 50%; }
        .card-label {
            font-size: 9px; font-weight: 600;
            color: #1976d2; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .card-value {
            font-size: 16px; font-weight: 700; color: #0b2e66; margin-top: 4px;
        }
        .card-sub { font-size: 9px; color: #888; margin-top: 2px; }

        /* ── HIGHLIGHT BOX ── */
        .highlight-box {
            background: #fffbea; border-left: 3px solid #f59e0b;
            border-radius: 3px; padding: 8px 12px;
            margin: 8px 0; font-size: 10px; line-height: 1.5;
        }
        .highlight-box.alert { background: #fee2e2; border-left-color: #dc2626; }
        .highlight-box.success { background: #f0fdf4; border-left-color: #16a34a; }

        /* ── MONTH CARDS ── */
        .month-cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 8px;
        }
        .month-card {
            width: 25%;
            background: #f7f9fd;
            border: 1px solid #d0d9e8;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .month-card-empty { background: transparent !important; border-color: transparent !important; }
        .month-card-name   { font-size: 9px; font-weight: 700; color: #1976d2; text-transform: uppercase; }
        .month-card-liters { font-size: 12px; font-weight: 700; color: #0b2e66; margin: 3px 0 1px; }
        .month-card-cost   { font-size: 9px; color: #666; }

        /* ── DATA TABLES ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10px; }
        .data-table th {
            background: #1976d2; color: #fff;
            padding: 7px 10px; text-align: left;
            font-weight: 600; font-size: 9px; letter-spacing: 0.3px;
            border-radius: 2px;
        }
        .data-table th.tr, .data-table td.tr { text-align: right; }
        .data-table td { padding: 6px 10px; border-bottom: 1px solid #e8edf4; color: #333; }
        .data-table tbody tr:nth-child(even) { background: #f7f9fd; }
        .data-table tfoot td {
            background: #e8f0fb; font-weight: 700;
            padding: 7px 10px; border-top: 2px solid #1976d2;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .status-increase { background: #fee2e2; color: #991b1b; }
        .status-decrease { background: #dcfce7; color: #166534; }
        .status-maintain { background: #dbeafe; color: #1e40af; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 32px; padding-top: 12px;
            border-top: 1px solid #d0d9e8;
            text-align: center; font-size: 9px; color: #999; line-height: 1.6;
        }

        .text-warning { color: #dc2626; font-weight: 600; }
        .text-success { color: #16a34a; font-weight: 600; }
    </style>
</head>
<body>

    {{-- ── PAGE 1: TITLE PAGE ── --}}
    <div class="title-page">
        <div class="title-page-logo">
            <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="PGLU Logo">
        </div>
        <div class="title-page-heading">Yearly Vehicle Monitoring Report</div>
        <div class="title-page-subtitle">{{ $year }} Fleet Performance & Expense Summary</div>
        <div class="title-page-date">Generated: {{ now()->format('F d, Y h:i A') }}</div>
        @if($officeName)
            <div class="title-page-subtitle" style="margin-top: 20px;">Office: {{ $officeName }}</div>
        @endif
    </div>

    {{-- ── PAGE 2: EXECUTIVE SUMMARY & FINANCIAL OVERVIEW ── --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="PGLU Logo">
            </td>
            <td class="header-center">
                <div class="gov-title">Province of La Union</div>
                <div class="sub-title">Office of the Sangguniang Panlalawigan</div>
                <div class="doc-title">Fleet Management Annual Report</div>
            </td>
            <td class="header-logo">
                <img src="{{ public_path('images/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas">
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td><span class="label">Report Year:</span> {{ $year }}</td>
            <td><span class="label">Generated:</span> {{ now()->format('F d, Y') }}</td>
        </tr>
    </table>

    <h2>Financial Summary</h2>
    <table class="cards-table">
        <tr>
            <td class="card">
                <div class="card-label">Total Fuel Cost</div>
                <div class="card-value">₱{{ number_format($totalCost, 0) }}</div>
                <div class="card-sub">{{ number_format($totalLiters, 0) }}L consumed</div>
            </td>
            <td class="card">
                <div class="card-label">Total Maintenance</div>
                <div class="card-value">₱{{ number_format($totalMaintenanceCost, 0) }}</div>
                <div class="card-sub">{{ count($maintenanceRecords) }} activities</div>
            </td>
            <td class="card">
                <div class="card-label">Grand Total Expense</div>
                <div class="card-value">₱{{ number_format($grandTotalExpense, 0) }}</div>
                <div class="card-sub">System-wide cost</div>
            </td>
        </tr>
    </table>

    <table class="cards-table">
        <tr>
            <td class="card card-2col">
                <div class="card-label">Budget Allocated</div>
                <div class="card-value">₱{{ number_format($totalBudgetAllocated, 0) }}</div>
                <div class="card-sub">Total budget</div>
            </td>
            <td class="card card-2col">
                <div class="card-label">Budget Utilization</div>
                <div class="card-value">{{ $budgetUtilizationPercent }}%</div>
                <div class="card-sub">₱{{ number_format($totalBudgetUsed, 0) }} used</div>
            </td>
        </tr>
    </table>

    {{-- Executive Summary Highlights --}}
    <h3>Key Metrics</h3>
    <div class="highlight-box success">
        <strong>Average Monthly Fuel Cost:</strong> ₱{{ number_format($totalCost / 12, 0) }}
    </div>
    <div class="highlight-box success">
        <strong>Average Monthly Fuel Consumption:</strong> {{ number_format($totalLiters / 12, 0) }} liters
    </div>
    <div class="highlight-box">
        <strong>Peak Consumption Month:</strong> 
        {{ $highest['month'] ?? 'N/A'}} 
        ({{ number_format($highest['liters'] ?? 0, 0) }}L, ₱{{ number_format($highest['cost'] ?? 0, 0) }})
    </div>
    @if($budgetUtilizationPercent >= 90)
        <div class="highlight-box alert">
            <strong style="color: #991b1b;">⚠ Budget Alert:</strong> System-wide budget utilization is at {{ $budgetUtilizationPercent }}% — budget constraints may be imminent.
        </div>
    @else
        <div class="highlight-box success">
            <strong>Budget Status:</strong> System-wide budget at {{ $budgetUtilizationPercent }}% utilization — tracking within acceptable limits.
        </div>
    @endif

    {{-- ── PAGE 3: FUEL MONITORING DETAILS ── --}}
    <div class="page-break"></div>

    <h2>1. Fuel Monitoring Analysis</h2>

    <h3>Monthly Fuel Consumption Trend</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Month</th>
                <th class="tr">Liters Used</th>
                <th class="tr">Cost (₱)</th>
                <th class="tr">% of Yearly Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $m)
                <tr>
                    <td>{{ $m['month'] }}</td>
                    <td class="tr">{{ number_format($m['liters'], 0) }}</td>
                    <td class="tr">₱{{ number_format($m['cost'], 0) }}</td>
                    <td class="tr">{{ $totalLiters > 0 ? number_format(($m['liters'] / $totalLiters) * 100, 1) : 0 }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="tr"><strong>{{ number_format($totalLiters, 0) }}</strong></td>
                <td class="tr"><strong>₱{{ number_format($totalCost, 0) }}</strong></td>
                <td class="tr"><strong>100%</strong></td>
            </tr>
        </tfoot>
    </table>

    <h3 style="margin-top: 16px;">Top Vehicles by Fuel Consumption</h3>
    @if($topVehicles && $topVehicles->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th class="tr">Liters Used</th>
                    <th class="tr">Est. Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topVehicles->take(8) as $tv)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tv['vehicle']->vehicle_name }}</td>
                        <td>{{ $tv['vehicle']->plate_number }}</td>
                        <td class="tr">{{ number_format($tv['liters'], 0) }}</td>
                        <td class="tr">₱{{ number_format($tv['fuelCost'] ?? 0, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color:#999; font-size:10px; margin-top:6px;">No vehicle fuel data available.</p>
    @endif

    <h3 style="margin-top: 16px;">Monthly Breakdown by Office</h3>
    @if($monthlyFuelByOffice && $monthlyFuelByOffice->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Office</th>
                    <th>Month</th>
                    <th class="tr">Liters</th>
                    <th class="tr">Cost (₱)</th>
                    <th class="tr">% of Monthly</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyFuelByOffice->take(20) as $mf)
                    @php
                        $monthTotal = $monthlyFuelByOffice->where('month', $mf['month'])->sum('liters');
                        $percentage = $monthTotal > 0 ? number_format(($mf['liters'] / $monthTotal) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td>{{ $mf['office'] }}</td>
                        <td>{{ $mf['month'] }}</td>
                        <td class="tr">{{ number_format($mf['liters'], 0) }}</td>
                        <td class="tr">₱{{ number_format($mf['cost'], 0) }}</td>
                        <td class="tr">{{ $percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color:#999; font-size:10px;">No office fuel data available.</p>
    @endif

    {{-- ── PAGE 4: MAINTENANCE RECORDS ── --}}
    <div class="page-break"></div>

    <h2>2. Maintenance Records & Analysis</h2>

    <h3>Maintenance Summary</h3>
    <table class="cards-table">
        <tr>
            <td class="card card-2col">
                <div class="card-label">Total Maintenance Records</div>
                <div class="card-value">{{ count($maintenanceRecords) }}</div>
                <div class="card-sub">Completed activities</div>
            </td>
            <td class="card card-2col">
                <div class="card-label">Avg Cost per Maintenance</div>
                <div class="card-value">₱{{ count($maintenanceRecords) > 0 ? number_format($totalMaintenanceCost / count($maintenanceRecords), 0) : 0 }}</div>
                <div class="card-sub">{{ $year }} average</div>
            </td>
        </tr>
    </table>

    <h3>Maintenance by Type</h3>
    @if($maintenanceByType && $maintenanceByType->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Maintenance Type</th>
                    <th class="tr">Count</th>
                    <th class="tr">Total Cost (₱)</th>
                    <th class="tr">Avg Cost (₱)</th>
                    <th class="tr">% of Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenanceByType as $mt)
                    <tr>
                        <td>{{ ucfirst($mt['type']) }}</td>
                        <td class="tr">{{ $mt['count'] }}</td>
                        <td class="tr">₱{{ number_format($mt['totalCost'], 0) }}</td>
                        <td class="tr">₱{{ number_format($mt['averageCost'], 0) }}</td>
                        <td class="tr">{{ $totalMaintenanceCost > 0 ? number_format(($mt['totalCost'] / $totalMaintenanceCost) * 100, 1) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td class="tr"><strong>{{ $maintenanceByType->sum('count') }}</strong></td>
                    <td class="tr"><strong>₱{{ number_format($totalMaintenanceCost, 0) }}</strong></td>
                    <td class="tr"><strong>₱{{ number_format($totalMaintenanceCost / max(1, $maintenanceByType->sum('count')), 0) }}</strong></td>
                    <td class="tr"><strong>100%</strong></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="color:#999; font-size:10px;">No maintenance data available for {{ $year }}.</p>
    @endif

    <h3 style="margin-top: 16px;">Vehicles with Highest Maintenance Costs</h3>
    @if($vehiclesWithHighMaintenance && $vehiclesWithHighMaintenance->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th class="tr">Maintenance Count</th>
                    <th class="tr">Total Cost (₱)</th>
                    <th class="tr">Avg Cost (₱)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehiclesWithHighMaintenance as $vm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vm['vehicle']->vehicle_name }}</td>
                        <td>{{ $vm['vehicle']->plate_number }}</td>
                        <td class="tr">{{ $vm['maintenanceCount'] }}</td>
                        <td class="tr">₱{{ number_format($vm['totalCost'], 0) }}</td>
                        <td class="tr">₱{{ number_format($vm['averageCost'], 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="highlight-box alert" style="margin-top: 8px;">
            <strong>⚠ Note:</strong> Vehicles listed above show elevated maintenance costs and may require attention or further investigation into usage patterns or mechanical issues.
        </div>
    @else
        <p style="color:#999; font-size:10px;">No vehicle maintenance data available.</p>
    @endif

    <h3 style="margin-top: 16px;">Vehicles Requiring Urgent Attention</h3>
    @if($vehiclesNeedingAttention && $vehiclesNeedingAttention->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th class="tr">Current KM</th>
                    <th class="tr">Last Maintenance KM</th>
                    <th class="tr">KM Since Maintenance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehiclesNeedingAttention as $vm)
                    @php
                        $latestFuelSlip = $vm->fuelSlips()->latest('date')->first();
                        $latestMaintenance = $vm->maintenances()->latest('date')->first();
                        $currentKm = $latestFuelSlip->km_reading ?? $vm->current_km ?? 0;
                        $lastMaintenanceKm = $latestMaintenance ? $latestMaintenance->maintenance_km : 0;
                        $kmDiff = $currentKm - $lastMaintenanceKm;
                    @endphp
                    <tr style="background-color: #fee2e2;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vm->vehicle_name }}</td>
                        <td>{{ $vm->plate_number }}</td>
                        <td class="tr">{{ number_format($currentKm, 0) }}</td>
                        <td class="tr">{{ number_format($lastMaintenanceKm, 0) }}</td>
                        <td class="tr"><strong class="text-warning">{{ number_format($kmDiff, 0) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="highlight-box alert" style="margin-top: 8px;">
            <strong>⚠ Maintenance Alert:</strong> {{ $vehiclesNeedingAttention->count() }} vehicle(s) have exceeded 5,000 km since last maintenance and require immediate attention.
        </div>
    @else
        <div class="highlight-box success">
            <strong>✓ Status:</strong> All vehicles are within acceptable maintenance intervals.
        </div>
    @endif

    {{-- ── PAGE 5: BOARD MEMBER BUDGET ── --}}
    <div class="page-break"></div>

    <h2>3. Board Member Budget Analysis</h2>

    <h3>Budget Utilization Summary</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Board Member / Office</th>
                <th class="tr">Allocated Budget</th>
                <th class="tr">Amount Used</th>
                <th class="tr">Remaining</th>
                <th class="tr">Usage %</th>
                <th>Recommendation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boardmembersData as $bm)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $bm['user']->name }}</strong><br>
                        <span style="font-size:9px; color:#666;">{{ $bm['user']->office?->name ?? 'No Office' }}</span>
                    </td>
                    <td class="tr">₱{{ number_format($bm['yearlyBudget'], 0) }}</td>
                    <td class="tr">₱{{ number_format($bm['totalUsed'], 0) }}</td>
                    <td class="tr">₱{{ number_format($bm['remaining'], 0) }}</td>
                    <td class="tr"><strong>{{ $bm['usedPercent'] }}%</strong></td>
                    <td>
                        <span class="status-badge status-{{ strtolower($bm['status']) }}">
                            {{ $bm['status'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>TOTAL / AVERAGE</strong></td>
                <td class="tr"><strong>₱{{ number_format($totalBudgetAllocated, 0) }}</strong></td>
                <td class="tr"><strong>₱{{ number_format($totalBudgetUsed, 0) }}</strong></td>
                <td class="tr"><strong>₱{{ number_format($totalBudgetRemaining, 0) }}</strong></td>
                <td class="tr"><strong>{{ $budgetUtilizationPercent }}%</strong></td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>

    @if($boardmembersWithAlerts && $boardmembersWithAlerts->count())
        <h3 style="margin-top: 16px;">⚠ Budget Alerts - Board Members Near Limit</h3>
        @foreach($boardmembersWithAlerts as $alert)
            <div class="highlight-box alert">
                <strong>{{ $alert['user']->name }}:</strong> 
                Budget utilization at {{ $alert['usedPercent'] }}% (₱{{ number_format($alert['totalUsed'], 0) }} / ₱{{ number_format($alert['yearlyBudget'], 0) }})
                — Only ₱{{ number_format($alert['remaining'], 0) }} remaining.
            </div>
        @endforeach
    @endif

    {{-- ── PAGE 6: VEHICLE SUMMARY ── --}}
    <div class="page-break"></div>

    <h2>4. Vehicle & Fleet Summary</h2>

    <table class="cards-table">
        <tr>
            <td class="card">
                <div class="card-label">Total Vehicles</div>
                <div class="card-value">{{ $topVehicles->count() + ($vehicles->count() - $topVehicles->count()) }}</div>
                <div class="card-sub">In the PGLU fleet</div>
            </td>
            <td class="card">
                <div class="card-label">Active Vehicles</div>
                <div class="card-value">{{ $topVehicles->count() }}</div>
                <div class="card-sub">With fuel usage {{ $year }}</div>
            </td>
            <td class="card">
                <div class="card-label">Avg Cost per Vehicle</div>
                <div class="card-value">₱{{ $topVehicles->count() > 0 ? number_format($grandTotalExpense / $topVehicles->count(), 0) : 0 }}</div>
                <div class="card-sub">Operating cost</div>
            </td>
        </tr>
    </table>

    <h3>Highest Operating Cost Vehicles</h3>
    @if($highCostVehicles && $highCostVehicles->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Vehicle</th>
                    <th>Plate</th>
                    <th>Assigned To</th>
                    <th class="tr">Fuel Cost</th>
                    <th class="tr">Maintenance</th>
                    <th class="tr">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($highCostVehicles as $hc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $hc['vehicle']->vehicle_name }}</td>
                        <td>{{ $hc['vehicle']->plate_number }}</td>
                        <td>{{ $hc['vehicle']->bm?->name ?? ($hc['vehicle']->office?->name ?? '—') }}</td>
                        <td class="tr">₱{{ number_format($hc['fuelCost'], 0) }}</td>
                        <td class="tr">₱{{ number_format($hc['maintenanceCost'], 0) }}</td>
                        <td class="tr"><strong>₱{{ number_format($hc['totalCost'], 0) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- ── PAGE 7: INSIGHTS & RECOMMENDATIONS ── --}}
    <div class="page-break"></div>

    <h2>5. Insights & Recommendations</h2>

    <h3>Cost Optimization Opportunities</h3>
    <div class="highlight-box" style="margin-bottom: 10px;">
        <strong style="color: #1976d2;">1. High-Cost Vehicle Review</strong><br>
        {{ $highCostVehicles->count() }} vehicle(s) represent significant operating costs. Recommend:
        <ul style="margin: 6px 0 0 16px; font-size: 10px; color: #333;">
            <li>Review usage patterns and reassign if possible</li>
            <li>Conduct maintenance cost audit to identify inefficiencies</li>
            <li>Transition to more fuel-efficient vehicles if feasible</li>
        </ul>
    </div>

    <div class="highlight-box" style="margin-bottom: 10px;">
        <strong style="color: #1976d2;">2. Maintenance Planning</strong><br>
        @if($vehiclesNeedingAttention && $vehiclesNeedingAttention->count())
            {{ $vehiclesNeedingAttention->count() }} vehicle(s) require urgent maintenance. Prioritize servicing within 30 days to prevent breakdowns and higher repair costs.
        @else
            All vehicles are within acceptable maintenance intervals. Continue current maintenance schedule.
        @endif
    </div>

    <div class="highlight-box" style="margin-bottom: 10px;">
        <strong style="color: #1976d2;">3. Fuel Efficiency</strong><br>
        Peak fuel consumption: {{ $highest['month'] ?? 'N/A' }} ({{ number_format($highest['liters'] ?? 0, 0) }}L)<br>
        Average monthly consumption: {{ number_format($totalLiters / 12, 0) }}L<br>
        <em>Recommend analysis of seasonal variations and routing optimization.</em>
    </div>

    <div class="highlight-box" style="margin-bottom: 10px;">
        <strong style="color: #1976d2;">4. Budget Management</strong><br>
        @if($budgetUtilizationPercent >= 90)
            <strong class="text-warning">⚠ Critical:</strong> Budget utilization at {{ $budgetUtilizationPercent }}% — review budget allocations and expense control measures.
        @elseif($budgetUtilizationPercent >= 75)
            Budget utilization at {{ $budgetUtilizationPercent }}% — monitor closely for Year {{ $year + 1 }} planning.
        @else
            Budget utilization at {{ $budgetUtilizationPercent }}% — good control. Review allocation efficiency for next year.
        @endif
    </div>

    <h3 style="margin-top: 16px;">Maintenance Cost Analysis</h3>
    <div class="highlight-box">
        <strong>Top Maintenance Issue:</strong>
        @if($maintenanceByType && $maintenanceByType->count())
            {{ ucfirst($maintenanceByType->first()['type']) }} 
            ({{ $maintenanceByType->first()['count'] }} occurrences, ₱{{ number_format($maintenanceByType->first()['totalCost'], 0) }})
        @else
            No data available
        @endif
        <br><em style="font-size: 9px;">Recommend predictive maintenance programs to reduce repetitive repairs.</em>
    </div>

    <h3 style="margin-top: 16px;">Recommendations Summary</h3>
    <table style="width:100%; border-collapse: collapse; font-size: 10px; margin-top: 8px;">
        <tbody>
            <tr style="background: #f7f9fd;">
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8; width: 50%; font-weight: 600;">Immediate Actions (Within 30 days)</td>
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8;">
                    • Schedule maintenance for vehicles exceeding service intervals<br>
                    • Review fleet utility and identify underused vehicles
                </td>
            </tr>
            <tr style="background: #fff;">
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8; font-weight: 600;">Short-term (Within 90 days)</td>
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8;">
                    • Implement fuel efficiency monitoring program<br>
                    • Adjust budget allocations based on utilization trends
                </td>
            </tr>
            <tr style="background: #f7f9fd;">
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8; font-weight: 600;">Strategic (Within 1 year)</td>
                <td style="padding: 8px 12px; border-bottom: 1px solid #d0d9e8;">
                    • Evaluate vehicle replacement or upgrade program<br>
                    • Establish preventive maintenance contracts
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This comprehensive report was generated from the BM Vehicle Monitoring System.</p>
        <p>For questions or additional analysis, please contact the Fleet Management office.</p>
        <p>Report generated on {{ now()->format('F d, Y \\a\\t h:i A') }}</p>
    </div>

</body>
</html>
