<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boardmember Yearly Dashboard PDF</title>
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
            border-bottom: 2px solid #1976d2;
            padding-bottom: 10px;
        }
        .header-table td { vertical-align: middle; padding-bottom: 10px; }
        .header-logo { width: 36px; text-align: center; padding: 0 4px; }
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
        .meta-table td { padding: 7px 12px; font-size: 10.5px; color: #444; width: 50%; }
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
            background: #f7f9fd;
            border: 1px solid #d0d9e8;
            border-radius: 5px;
            padding: 10px 14px;
            text-align: center;
            vertical-align: middle;
        }
        .card-label {
            font-size: 10px; font-weight: 600;
            color: #1976d2; text-transform: uppercase; letter-spacing: 0.3px;
        }
        .card-value {
            font-size: 16px; font-weight: 700; color: #222; margin-top: 4px;
        }
        .card-sub { font-size: 9.5px; color: #888; margin-top: 2px; }

        /* ── MONTH CARDS (4 per row) ── */
        .month-cards-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 4px;
        }
        .month-card {
            width: 25%;
            background: #f7f9fd;
            border: 1px solid #d0d9e8;
            border-radius: 4px;
            padding: 7px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .month-card-empty { background: transparent !important; border-color: transparent !important; }
        .month-card-name   { font-size: 9.5px; font-weight: 700; color: #1976d2; text-transform: uppercase; }
        .month-card-liters { font-size: 12px; font-weight: 700; color: #222; margin: 3px 0 1px; }
        .month-card-cost   { font-size: 9.5px; color: #777; }

        /* ── DATA TABLES ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 10.5px; }
        .data-table th {
            background: #1976d2; color: #fff;
            padding: 7px 10px; text-align: left;
            font-weight: 600; font-size: 10px; letter-spacing: 0.3px;
        }
        .data-table th.tr, .data-table td.tr { text-align: right; }
        .data-table td { padding: 6px 10px; border-bottom: 1px solid #e8edf4; color: #333; }
        .data-table tbody tr:nth-child(even) { background: #f7f9fd; }
        .data-table tfoot td {
            background: #e8f0fb; font-weight: 700;
            padding: 7px 10px; border-top: 2px solid #c0cfe8;
        }

        /* ── PROGRESS BAR ── */
        .bar-container {
            background: #e2e8f0;
            border-radius: 12px;
            height: 12px;
            overflow: hidden;
            margin: 8px 0;
        }
        .bar-fill {
            height: 100%;
            border-radius: 12px;
        }
        .bar-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .bar-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .bar-red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        /* ── STATUS COLORS ── */
        .status-high   { color: #dc2626; font-weight: 600; }
        .status-medium { color: #f59e0b; font-weight: 600; }
        .status-low    { color: #059669; font-weight: 600; }

        /* ── HIGHLIGHT ── */
        .highlight-box {
            background: #f0f4fb; border-left: 3px solid #1976d2;
            border-radius: 3px; padding: 8px 12px;
            margin-top: 6px; font-size: 10.5px;
        }

        /* ── RECOMMENDATION ── */
        .recommendation {
            padding: 10px 14px;
            border-radius: 4px;
            margin: 8px 0 16px;
        }
        .recommendation-increase { background: #fee2e2; border-left: 3px solid #dc2626; }
        .recommendation-maintain { background: #d1fae5; border-left: 3px solid #059669; }
        .recommendation-decrease { background: #fef3c7; border-left: 3px solid #f59e0b; }
        .recommendation-monitor { background: #f0f4fb; border-left: 3px solid #1976d2; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px; padding-top: 12px;
            border-top: 1px solid #e0e0e0;
            text-align: center; font-size: 9.5px; color: #999; line-height: 1.6;
        }

        /* ── NO VEHICLE ── */
        .no-vehicle {
            padding: 20px;
            background: #f8fafc;
            border-radius: 5px;
            text-align: center;
            color: #64748b;
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
                <div class="doc-title">Board Member Yearly Report</div>
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
            <td><span class="label">Year:</span> {{ now()->year }}</td>
        </tr>
    </table>

    @if($vehicle)
        {{-- ── SUMMARY CARDS ── --}}
        <h2>Yearly Overview ({{ now()->year }})</h2>
        <table class="cards-table">
            <tr>
                <td class="card">
                    <div class="card-label">Total Liters Used</div>
                    <div class="card-value">{{ number_format($totalLiters ?? 0, 2) }} L</div>
                    <div class="card-sub">Full Year {{ now()->year }}</div>
                </td>
                <td class="card">
                    <div class="card-label">Budget Status</div>
                    <div class="card-value {{ $budgetUsedPercentage >= 80 ? 'status-high' : ($budgetUsedPercentage >= 50 ? 'status-medium' : 'status-low') }}">{{ $budgetUsedPercentage }}%</div>
                    <div class="card-sub">&#8369;{{ number_format($yearlyBudget - $remainingBudget, 0) }} of &#8369;{{ number_format($yearlyBudget, 0) }}</div>
                </td>
            </tr>
        </table>

        {{-- ── VEHICLE INFORMATION ── --}}
        <h2>Vehicle Information</h2>
        <table class="meta-table">
            <tr>
                <td><span class="label">Plate Number:</span> {{ $vehicle->plate_number }}</td>
                <td><span class="label">Monthly Fuel Limit:</span> {{ $monthlyLimit }} liters</td>
            </tr>
        </table>

        {{-- ── BUDGET PROGRESS ── --}}
        <h2>Budget Overview</h2>
        <table class="meta-table">
            <tr>
                <td><span class="label">Yearly Budget:</span> &#8369;{{ number_format($yearlyBudget, 2) }}</td>
                <td><span class="label">Remaining:</span> &#8369;{{ number_format($remainingBudget, 2) }}</td>
            </tr>
        </table>

        <div class="bar-container">
            <div class="bar-fill {{ $budgetUsedPercentage >= 80 ? 'bar-red' : ($budgetUsedPercentage >= 50 ? 'bar-orange' : 'bar-blue') }}" style="width: {{ min(max($budgetUsedPercentage, 0), 100) }}%;"></div>
        </div>

        {{-- ── BUDGET RECOMMENDATION ── --}}
        @php
            $remainingPercent = 100 - $budgetUsedPercentage;
        @endphp

        <h3>Fiscal Recommendation</h3>
        @if($budgetUsedPercentage >= 90)
            <div class="recommendation recommendation-increase">
                <strong class="status-high">📈 Increase Budget</strong>
                <p style="margin-top: 4px; font-size: 10px; color: #666;">
                    More than 90% of the annual budget has been utilized. Additional allocation may be required to sustain operations.
                </p>
            </div>
        @elseif($remainingPercent >= 25 && $remainingPercent <= 30)
            <div class="recommendation recommendation-maintain">
                <strong class="status-low">✓ Maintain Budget</strong>
                <p style="margin-top: 4px; font-size: 10px; color: #666;">
                    Remaining budget is within the ideal savings range. Current allocation is sufficient.
                </p>
            </div>
        @elseif($remainingBudget >= 30000)
            <div class="recommendation recommendation-decrease">
                <strong class="status-medium">📉 Consider Decrease</strong>
                <p style="margin-top: 4px; font-size: 10px; color: #666;">
                    A significant portion of the budget remains unused. Reducing next year's allocation may improve fiscal efficiency.
                </p>
            </div>
        @else
            <div class="recommendation recommendation-monitor">
                <strong>📊 Monitor Usage</strong>
                <p style="margin-top: 4px; font-size: 10px; color: #666;">
                    Budget utilization is within acceptable limits. Continued monitoring is advised.
                </p>
            </div>
        @endif

        {{-- ── PER-MONTH CARDS ── --}}
        @if(!empty($monthlyData))
            <h2>Monthly Breakdown</h2>
            @php
                $monthArr = is_array($monthlyData) ? $monthlyData : $monthlyData->toArray();
                $monthChunks = array_chunk($monthArr, 4);
            @endphp
            @foreach($monthChunks as $row)
                <table class="month-cards-table">
                    <tr>
                        @foreach($row as $m)
                            <td class="month-card">
                                <div class="month-card-name">{{ $m['month'] }}</div>
                                <div class="month-card-liters">{{ number_format($m['liters'], 2) }} L</div>
                                <div class="month-card-cost">&#8369;{{ number_format($m['cost'], 2) }}</div>
                            </td>
                        @endforeach
                        @for($i = count($row); $i < 4; $i++)
                            <td class="month-card month-card-empty"></td>
                        @endfor
                    </tr>
                </table>
            @endforeach
        @endif

        {{-- ── MONTHLY DETAIL TABLE ── --}}
        @if(!empty($monthlyData))
            <h2>Monthly Detail</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="tr">Liters Used</th>
                        <th class="tr">Cost (&#8369;)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $m)
                        <tr>
                            <td>{{ $m['month'] }}</td>
                            <td class="tr">{{ number_format($m['liters'], 2) }}</td>
                            <td class="tr">&#8369;{{ number_format($m['cost'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="tr"><strong>{{ number_format($totalLiters ?? 0, 2) }}</strong></td>
                        <td class="tr"><strong>&#8369;{{ number_format($totalCost ?? 0, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        {{-- ── SUMMARY STATISTICS ── --}}
        <h2>Summary Statistics</h2>
        <div class="highlight-box">
            <span class="label">Average Monthly Usage:</span> {{ number_format(($totalLiters ?? 0) / 12, 2) }} liters<br>
            <span class="label">Average Monthly Cost:</span> &#8369;{{ number_format(($totalCost ?? 0) / 12, 2) }}
        </div>
    @else
        <div class="no-vehicle">
            <strong style="color: #dc2626;">No vehicle assigned.</strong>
            <p style="margin-top: 8px; font-size: 10px;">Please contact your administrator to assign a vehicle to your account.</p>
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This is a yearly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>

</body>
</html>
