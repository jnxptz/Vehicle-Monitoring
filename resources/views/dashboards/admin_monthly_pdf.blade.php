<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Monthly Dashboard PDF</title>
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
        .page-break { page-break-after: always; margin-top: 30px; }

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
        .gov-title { font-size: 14px; font-weight: 700; color: #0b2e66; letter-spacing: 0.5px; }
        .sub-title  { font-size: 10px; color: #555; margin-top: 2px; }
        .doc-title  { font-size: 12px; font-weight: 600; color: #222; margin-top: 4px; }

        /* ── META ── */
        .meta-table {
            width: 100%; border-collapse: collapse; margin-bottom: 14px;
            background: #f0f4fb; border-radius: 4px;
        }
        .meta-table td { padding: 6px 12px; font-size: 9.5px; color: #444; }
        .label { font-weight: 600; color: #1976d2; }

        /* ── SECTION TITLES ── */
        h2 {
            font-size: 11.5px; font-weight: 700; color: #0b2e66;
            margin: 14px 0 8px; padding-bottom: 4px;
            border-bottom: 2px solid #1976d2;
            text-transform: uppercase; letter-spacing: 0.4px;
        }
        h3 { font-size: 10.5px; font-weight: 600; color: #1976d2; margin: 10px 0 6px; }

        /* ── SUMMARY CARDS ── */
        .cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 10px;
        }
        .card {
            width: 50%;
            background: #f7f9fd;
            border: 1px solid #d0d9e8;
            border-radius: 5px;
            padding: 10px 12px;
            text-align: center;
            vertical-align: middle;
        }
        .card-label {
            font-size: 9px; font-weight: 600;
            color: #1976d2; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .card-value { font-size: 15px; font-weight: 700; color: #0b2e66; margin-top: 3px; }
        .card-sub   { font-size: 9px; color: #666; margin-top: 2px; }

        /* ── DATA TABLES ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 9.5px; }
        .data-table th {
            background: #1976d2; color: #fff;
            padding: 6px 9px; text-align: left;
            font-weight: 600; font-size: 9px; letter-spacing: 0.3px;
        }
        .data-table th.tr { text-align: right; }
        .data-table td {
            padding: 6px 9px;
            color: #333; vertical-align: middle;
        }
        .data-table td.tr { text-align: right; }
        .data-table tbody tr:nth-child(even) { background: #f7f9fd; }
        .data-table tfoot td {
            background: #e8f0fb; font-weight: 700;
            padding: 6px 9px; border-top: 2px solid #1976d2;
        }

        /* ── STATUS COLORS ── */
        .status-danger  { color: #dc2626; font-weight: 700; }
        .status-warning { color: #d97706; font-weight: 700; }
        .status-normal  { color: #059669; font-weight: 700; }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-success  { background: #d1fae5; color: #065f46; }

        /* ── HIGHLIGHT BOX ── */
        .highlight-box {
            background: #f9fafb; border-left: 3px solid #1976d2;
            border-radius: 3px; padding: 8px 10px;
            margin: 8px 0; font-size: 9.5px; line-height: 1.5; border: 1px solid #e5e7eb;
        }
        .highlight-box.alert { border-left-color: #dc2626; background: #fef2f2; }
        .highlight-box.warning { border-left-color: #f59e0b; background: #fffbeb; }
        .highlight-box.success { border-left-color: #10b981; background: #f0fdf4; }

        /* ── VEHICLE SECTION ── */
        .vehicle-title {
            font-size: 10px; font-weight: 600; color: #1e293b;
            margin: 10px 0 5px; padding: 6px 9px;
            background: #f0f4fb; border-radius: 3px;
            border-left: 3px solid #1976d2;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 24px; padding-top: 10px;
            border-top: 1px solid #d0d9e8;
            text-align: center; font-size: 9px; color: #999; line-height: 1.5;
        }

        .text-right { text-align: right; }
        .fw-bold { font-weight: 700; }
        ul { margin: 4px 0 0 16px; padding: 0; }
        li { margin: 2px 0; }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="PGLU Logo">
            </td>
            <td class="header-center">
                <div class="gov-title">Province of La Union</div>
                <div class="sub-title">Office of the Sangguniang Panlalawigan</div>
                <div class="doc-title">Board Member Monthly Report</div>
            </td>
            <td class="header-logo">
                <img src="{{ public_path('images/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas">
            </td>
        </tr>
    </table>

    {{-- ── META ── --}}
    <table class="meta-table">
        <tr>
            <td><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</td>
            <td><span class="label">Period:</span> {{ $selectedMonthName }} {{ $year }}</td>
            @if($officeName)
                <td><span class="label">Office:</span> {{ $officeName }}</td>
            @endif
        </tr>
    </table>

    {{-- ── 1. MONTHLY OVERVIEW ── --}}
    <h2>1. Monthly Overview ({{ $selectedMonthName }} {{ $year }})</h2>
    <table class="cards-table">
        <tr>
            <td class="card">
                <div class="card-label">Total Liters Used</div>
                <div class="card-value">{{ number_format($totalMonthlyLiters, 2) }} L</div>
                <div class="card-sub">Fleet-wide consumption</div>
            </td>
            <td class="card">
                <div class="card-label">Total Monthly Cost</div>
                <div class="card-value">₱{{ number_format($totalMonthlyCost, 0) }}</div>
                <div class="card-sub">Fuel + Maintenance</div>
            </td>
        </tr>
    </table>

    <div class="highlight-box @if(strpos($usageAssessment, 'High') !== false) warning @elseif(strpos($usageAssessment, 'Low') !== false) success @endif">
        <strong>Usage Assessment:</strong> {{ $usageAssessment }}
        @if(strpos($usageAssessment, 'High') !== false)
            — Monthly spending is above normal levels. Review recommended for efficient budget management.
        @elseif(strpos($usageAssessment, 'Low') !== false)
            — Monthly spending is below average. Fleet usage is within controlled limits.
        @else
            — Monthly spending is within normal parameters.
        @endif
    </div>

    {{-- ── 2. BOARD MEMBER PERFORMANCE ── --}}
    <h2>2. Board Member Performance ({{ $selectedMonthName }} {{ $year }})</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:28px;">#</th>
                <th>Board Member</th>
                <th class="tr">Monthly Liters</th>
                <th class="tr">Monthly Cost</th>
                <th class="tr">Yearly Budget</th>
                <th class="tr">Yearly Used</th>
                <th class="tr">Remaining</th>
                <th class="tr">Usage %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr @if($row['budgetUsedPercentage'] >= 90) style="background: #fee2e2;" @elseif($row['budgetUsedPercentage'] >= 80) style="background: #fef3c7;" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $row['user']->name }}</strong>
                        @if($row['monthlyLitersUsed'] == 0)
                            <br><span style="font-size:8px; color:#666;"><em>No usage this month</em></span>
                        @endif
                    </td>
                    <td class="tr">{{ number_format($row['monthlyLitersUsed'], 2) }}</td>
                    <td class="tr">₱{{ number_format($row['monthlyCostUsed'], 0) }}</td>
                    <td class="tr">₱{{ number_format($row['yearlyBudget'], 0) }}</td>
                    <td class="tr">₱{{ number_format($row['totalUsed'], 0) }}</td>
                    <td class="tr @if($row['remainingBudget'] < 0) status-danger @elseif($row['budgetUsedPercentage'] >= 80) status-warning @endif">
                        ₱{{ number_format($row['remainingBudget'], 0) }}
                    </td>
                    <td class="tr @if($row['budgetUsedPercentage'] >= 90) status-danger @elseif($row['budgetUsedPercentage'] >= 80) status-warning @endif">
                        <strong>{{ $row['budgetUsedPercentage'] }}%</strong>
                        @if($row['budgetUsedPercentage'] >= 90)
                            <br><span class="badge badge-danger">Critical</span>
                        @elseif($row['budgetUsedPercentage'] >= 80)
                            <br><span class="badge badge-warning">Alert</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding:12px; text-align:center; font-style:italic; color:#999;">
                        No board members found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── 3. VEHICLE BREAKDOWN ── --}}
    <h2>3. Vehicle Breakdown and Costs</h2>
    @if($rows->count() > 0)
        @foreach($rows as $rowIdx => $row)
            @if(count($row['vehicles']) > 0)
                <div class="vehicle-title">{{ $row['user']->name }} — Vehicle Details</div>
                <table class="data-table" style="margin-bottom: 8px;">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th class="tr">Plate</th>
                            <th class="tr">Fuel Cost</th>
                            <th class="tr">Maintenance</th>
                            <th class="tr">Total Cost</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row['vehicles'] as $vehicle)
                            @php
                                $vTotal = $vehicle['fuelSlipCost'] + $vehicle['maintenanceCost'];
                                $avgCost = $totalMonthlyCost / max(1, count($allVehicleCosts ?? []));
                                $isHighCost = $vTotal > ($avgCost * 1.5);
                            @endphp
                            <tr @if($isHighCost) style="background: #fef3c7;" @endif>
                                <td>{{ $vehicle['vehicle']->vehicle_name }}</td>
                                <td class="tr">{{ $vehicle['vehicle']->plate_number }}</td>
                                <td class="tr">₱{{ number_format($vehicle['fuelSlipCost'], 0) }}</td>
                                <td class="tr">₱{{ number_format($vehicle['maintenanceCost'], 0) }}</td>
                                <td class="tr"><strong>₱{{ number_format($vTotal, 0) }}</strong></td>
                                <td>
                                    @if($vehicle['maintenanceCost'] > 0 && $vehicle['maintenanceCost'] > $avgCost)
                                        <span class="badge badge-warning">High Maint.</span>
                                    @elseif($isHighCost)
                                        <span class="badge badge-warning">High Cost</span>
                                    @else
                                        <span class="badge badge-success">Normal</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

    {{-- ── PAGE BREAK ── --}}
    <div class="page-break"></div>

    {{-- ── 4. MONTHLY ANALYSIS ── --}}
    <h2>4. Monthly Analysis</h2>
    
    <h3>Overall Fuel & Maintenance Consumption</h3>
    <div class="highlight-box">
        The fleet consumed a total of <strong>{{ number_format($totalMonthlyLiters, 0) }} liters</strong> of fuel during {{ $selectedMonthName }}, 
        translating to a fuel cost of <strong>₱{{ number_format($totalMonthlyCost, 0) }}</strong>. 
        @if($totalMonthlyCost > $averageMonthlyCost * 1.2)
            This represents approximately <strong>{{ round(($totalMonthlyCost / ($averageMonthlyCost * 12)) * 100) }}% above</strong> the typical monthly allocation.
        @else
            This represents a <strong>controlled expense level</strong> within budget parameters.
        @endif
    </div>

    <h3>Budget Utilization Status</h3>
    <div class="highlight-box">
        @if($boardMembersExceeded->count() > 0)
            <strong style="color: #dc2626;">⚠ Critical Alert:</strong>
            {{ $boardMembersExceeded->count() }} board member(s) have <strong>EXCEEDED their yearly budget</strong>:
            <ul>
                @foreach($boardMembersExceeded as $bm)
                    <li><strong>{{ $bm['user']->name }}</strong> — Overspent by ₱{{ number_format(abs($bm['remainingBudget']), 0) }}</li>
                @endforeach
            </ul>
            Immediate action required to address overspending.
        @elseif($boardMembersNearLimit->count() > 0)
            <strong style="color: #d97706;">⚠ Budget Warning:</strong>
            {{ $boardMembersNearLimit->count() }} board member(s) are approaching their yearly budget limit (80%+). Monitor closely.
        @else
            Budget utilization is within acceptable ranges across all board members.
        @endif
    </div>

    @if($boardMembersNoUsage->count() > 0)
        <h3>Inactive Board Members (No Usage)</h3>
        <div class="highlight-box success">
            <strong>{{ $boardMembersNoUsage->count() }} board member(s)</strong> had no fuel or maintenance activity in {{ $selectedMonthName }}:
            <ul>
                @foreach($boardMembersNoUsage as $bm)
                    <li>{{ $bm['user']->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── 5. KEY FINDINGS ── --}}
    <h2>5. Key Findings</h2>

    <h3>Highest Monthly Expenses</h3>
    <div class="highlight-box">
        <strong>Highest Monthly Spender:</strong>
        @if($highestMonthlySpender)
            {{ $highestMonthlySpender['user']->name }} spent ₱{{ number_format($highestMonthlySpender['monthlyCostUsed'], 0) }} 
            ({{ number_format($highestMonthlySpender['monthlyLitersUsed'], 0) }}L) in {{ $selectedMonthName }}.
        @else
            No data available.
        @endif
    </div>

    <div class="highlight-box">
        <strong>Highest Fuel Usage:</strong>
        @if($highestFuelUsage)
            {{ $highestFuelUsage['user']->name }} consumed {{ number_format($highestFuelUsage['monthlyLitersUsed'], 0) }} liters 
            (₱{{ number_format($highestFuelUsage['monthlyCostUsed'], 0) }}) in {{ $selectedMonthName }}.
        @else
            No data available.
        @endif
    </div>

    <div class="highlight-box">
        <strong>Highest Vehicle Operating Cost:</strong>
        @if($highestVehicleExpense)
            <strong>{{ $highestVehicleExpense['vehicle']->plate_number }}</strong> ({{ $highestVehicleExpense['vehicle']->vehicle_name }})
            assigned to {{ $highestVehicleExpense['boardMember'] }} — Total: ₱{{ number_format($highestVehicleExpense['totalCost'], 0) }}
            (Fuel: ₱{{ number_format($highestVehicleExpense['fuelCost'], 0) }}, Maintenance: ₱{{ number_format($highestVehicleExpense['maintenanceCost'], 0) }})
        @else
            No vehicle data available.
        @endif
    </div>

    @if($highFuelCostVehicles->count() > 0)
        <h3>Vehicles with High Fuel Costs</h3>
        <div class="highlight-box warning">
            <strong>Review Recommended:</strong> The following {{ $highFuelCostVehicles->count() }} vehicle(s) have notably high fuel expenses:
            <ul>
                @foreach($highFuelCostVehicles as $v)
                    <li><strong>{{ $v['vehicle']->plate_number }}</strong> — ₱{{ number_format($v['fuelCost'], 0) }} ({{ $v['boardMember'] }})</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($highMaintenanceCostVehicles->count() > 0)
        <h3>Vehicles with High Maintenance Costs</h3>
        <div class="highlight-box alert">
            <strong>Maintenance Alert:</strong> The following {{ $highMaintenanceCostVehicles->count() }} vehicle(s) have significant maintenance expenses:
            <ul>
                @foreach($highMaintenanceCostVehicles as $v)
                    <li><strong>{{ $v['vehicle']->plate_number }}</strong> — ₱{{ number_format($v['maintenanceCost'], 0) }} ({{ $v['boardMember'] }})</li>
                @endforeach
            </ul>
            Recommend mechanical inspection to identify underlying issues.
        </div>
    @endif

    {{-- ── 6. RECOMMENDATIONS ── --}}
    <h2>6. Recommendations</h2>

    @if($boardMembersExceeded->count() > 0)
        <h3>Critical Actions Required</h3>
        <div class="highlight-box alert">
            <strong>Budget Overspending:</strong> The following board member(s) have exceeded their yearly budget:
            <ul>
                @foreach($boardMembersExceeded as $bm)
                    <li><strong>{{ $bm['user']->name }}</strong> is at {{ $bm['budgetUsedPercentage'] }}% utilization. 
                    A budget review meeting is recommended immediately.</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h3>General Recommendations</h3>
    <div class="highlight-box">
        <ul>
            <li><strong>Review High-Cost Vehicles:</strong> 
                @if($highFuelCostVehicles->count() > 0)
                    {{ $highFuelCostVehicles->count() }} vehicle(s) with high fuel costs require driver coaching and route optimization.
                @else
                    Monitor fuel efficiency across all vehicles.
                @endif
            </li>
            <li><strong>Maintenance Scheduling:</strong> 
                @if($highMaintenanceCostVehicles->count() > 0)
                    {{ $highMaintenanceCostVehicles->count() }} vehicle(s) require immediate mechanical inspection to prevent costlier repairs.
                @else
                    Continue preventive maintenance schedule.
                @endif
            </li>
            <li><strong>Monitor Budget Leaders:</strong> 
                @if($boardMembersNearLimit->count() > 0)
                    {{ $boardMembersNearLimit->count() }} board member(s) are approaching budget limits. Implement spending controls and notify supervisors.
                @else
                    All board members are within budget parameters.
                @endif
            </li>
            <li><strong>Fuel Efficiency Program:</strong> Implement a tracking system for fuel consumption per vehicle to identify anomalies early.</li>
            <li><strong>Budget Adjustment for {{ $year + 1 }}:</strong> Review allocation based on YTD spending patterns and adjust accordingly.</li>
        </ul>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This is a monthly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
        <p>For questions or clarifications, please contact the Fleet Management Office.</p>
    </div>

</body>
</html>
