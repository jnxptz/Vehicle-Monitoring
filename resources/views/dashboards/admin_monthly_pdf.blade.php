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
            font-size: 11px;
            color: #333;
            padding: 24px 32px;
            background: #fff;
        }

        /* ── HEADER ── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .header-table td { vertical-align: middle; padding-bottom: 10px; }
        .header-logo { width: 36px; text-align: center; padding: 0 4px; }
        .header-logo img { width: 44px; height: auto; }
        .header-center { text-align: center; padding: 0 6px; }
        .gov-title { font-size: 14px; font-weight: 700; color: #111; letter-spacing: 0.5px; }
        .sub-title  { font-size: 10.5px; color: #555; margin-top: 2px; }
        .doc-title  { font-size: 13px; font-weight: 600; color: #222; margin-top: 4px; }

        /* ── META ── */
        .meta-table {
            width: 100%; border-collapse: collapse; margin-bottom: 16px;
            background: #f0f4fb; border-radius: 4px;
        }
        .meta-table td { padding: 7px 12px; font-size: 10.5px; color: #444; }
        .label { font-weight: 600; color: #1976d2; }

        /* ── SECTION TITLES ── */
        h2 {
            font-size: 11.5px; font-weight: 700; color: #1976d2;
            margin: 16px 0 8px; padding-bottom: 4px;
            border-bottom: 1px solid #d0d9e8;
            text-transform: uppercase; letter-spacing: 0.4px;
        }
        h3 { font-size: 11px; font-weight: 600; color: #444; margin: 12px 0 6px; }

        /* ── SUMMARY CARDS (2 side by side) ── */
        .cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 10px;
        }
        .card {
            width: 50%;
            background: #f0f4fb;
            border-radius: 5px;
            padding: 10px 14px;
            text-align: center;
            vertical-align: middle;
        }
        .card-label {
            font-size: 10px; font-weight: 600;
            color: #1976d2; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .card-value { font-size: 16px; font-weight: 700; color: #222; margin-top: 4px; }
        .card-sub   { font-size: 9.5px; color: #888; margin-top: 2px; }

        /* ── DATA TABLES ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 10.5px; }
        .data-table th {
            background: #1976d2; color: #fff;
            padding: 7px 10px; text-align: left;
            font-weight: 600; font-size: 10px; letter-spacing: 0.3px;
        }
        .data-table td {
            padding: 7px 10px;
            color: #444; font-size: 10.5px;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) { background: #f8fafc; }
        .data-table tbody tr:nth-child(even) { background: #f7f9fd; }
        .data-table tfoot td {
            background: #e8f0fb; font-weight: 700;
            padding: 7px 10px;
        }

        /* ── VEHICLE SECTION ── */
        .vehicle-title {
            font-size: 11px; font-weight: 600; color: #1e293b;
            margin: 12px 0 6px; padding: 7px 10px;
            background: #f0f4fb; border-radius: 4px;
            border-left: 3px solid #1976d2;
        }

        /* ── STATUS COLORS ── */
        .status-normal  { color: #059669; font-weight: 700; }
        .status-warning { color: #d97706; font-weight: 700; }
        .status-danger  { color: #dc2626; font-weight: 700; }

        /* ── BADGES ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-primary { background: #dbeafe; color: #1e40af; }

        /* ── HIGHLIGHT ── */
        .highlight-box {
            background: #f0f4fb; border-left: 3px solid #1976d2;
            border-radius: 3px; padding: 8px 12px;
            margin-top: 6px; font-size: 10.5px;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px; padding-top: 12px;
            border-top: 1px solid #e0e0e0;
            text-align: center; font-size: 9.5px; color: #999; line-height: 1.6;
        }
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

    {{-- ── SUMMARY CARDS ── --}}
    <h2>Monthly Overview ({{ $selectedMonthName }} {{ $year }})</h2>
    <table class="cards-table">
        <tr>
            <td class="card">
                <div class="card-label">Total Liters Used</div>
                <div class="card-value">{{ number_format($totalMonthlyLiters, 2) }} L</div>
                <div class="card-sub">{{ $selectedMonthName }} {{ $year }}</div>
            </td>
            <td class="card">
                <div class="card-label">Total Cost</div>
                <div class="card-value">&#8369;{{ number_format($totalMonthlyCost, 2) }}</div>
                <div class="card-sub">{{ $selectedMonthName }} {{ $year }}</div>
            </td>
        </tr>
    </table>

    {{-- ── BOARDMEMBER PERFORMANCE ── --}}
    <h2>Boardmember Performance ({{ $selectedMonthName }} {{ $year }})</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th class="tc" style="width:28px;">#</th>
                <th>Boardmember</th>
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
                <tr>
                    <td class="tc">{{ $loop->iteration }}</td>
                    <td>{{ $row['user']->name }}</td>
                    <td class="tr">{{ number_format($row['monthlyLitersUsed'], 2) }}</td>
                    <td class="tr">&#8369;{{ number_format($row['monthlyCostUsed'], 2) }}</td>
                    <td class="tr">&#8369;{{ number_format($row['yearlyBudget'], 2) }}</td>
                    <td class="tr">&#8369;{{ number_format($row['totalUsed'], 2) }}</td>
                    <td class="tr {{ $row['remainingBudget'] < 0 ? 'status-high' : ($row['budgetUsedPercentage'] >= 80 ? 'status-medium' : 'status-low') }}">
                        &#8369;{{ number_format($row['remainingBudget'], 2) }}
                    </td>
                    <td class="tr">
                        <span class="{{ $row['budgetUsedPercentage'] >= 90 ? 'status-high' : ($row['budgetUsedPercentage'] >= 80 ? 'status-medium' : 'status-low') }}">
                            {{ $row['budgetUsedPercentage'] }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="tc" style="padding:16px; font-style:italic; color:#999;">
                        No boardmembers found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── VEHICLE BREAKDOWN ── --}}
    @if($rows->count() > 0)
        <h2>Vehicle Breakdown</h2>
        @foreach($rows as $row)
            @if(count($row['vehicles']) > 0)
                <div class="vehicle-title">{{ $row['user']->name }} &mdash; Vehicle Details</div>
                <table class="data-table" style="margin-bottom:10px;">
                    <thead>
                        <tr>
                            <th>Vehicle Name</th>
                            <th class="tr">Plate Number</th>
                            <th class="tr">Fuel Cost</th>
                            <th class="tr">Maintenance Cost</th>
                            <th class="tr">Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row['vehicles'] as $vehicle)
                            <tr>
                                <td>{{ $vehicle['vehicle']->vehicle_name }}</td>
                                <td class="tr">{{ $vehicle['vehicle']->plate_number }}</td>
                                <td class="tr">&#8369;{{ number_format($vehicle['fuelSlipCost'], 2) }}</td>
                                <td class="tr">&#8369;{{ number_format($vehicle['maintenanceCost'], 2) }}</td>
                                <td class="tr"><strong>&#8369;{{ number_format($vehicle['fuelSlipCost'] + $vehicle['maintenanceCost'], 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This is a monthly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>

</body>
</html>