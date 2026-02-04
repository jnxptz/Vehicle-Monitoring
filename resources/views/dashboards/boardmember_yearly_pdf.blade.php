<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boardmember Yearly Dashboard PDF</title>
    <link rel="stylesheet" href="{{ public_path('css/boardmember_pdf.css') }}">
    <style>
        .monthly-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .monthly-table thead {
            background: #1976d2;
            color: #fff;
        }
        .monthly-table th {
            padding: 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #1976d2;
        }
        .monthly-table td {
            padding: 8px;
            border: 1px solid #e0e0e0;
        }
        .monthly-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .monthly-table tbody tr:hover {
            background: #f0f7ff;
        }
        .text-right {
            text-align: right;
        }
        .page-break {
            page-break-before: always;
        }x`
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom:6px;">
        <img src="{{ public_path('images/SP Seal.png') }}" alt="Logo" style="height:32px; margin-bottom:4px;">
    </div>
    <h1>Boardmember Yearly Dashboard</h1>
    <div class="meta">
        <div><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
        <div><span class="label">Year:</span> {{ now()->year }} (January to December)</div>
    </div>

    <div class="box">
        @if(!empty($alerts))
            <div class="alerts">
                <div class="label">Year-to-Date Alerts</div>
                <ul>
                    @foreach($alerts as $alert)
                        <li>{{ $alert }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($vehicle)
            <h2>Vehicle Information</h2>
            <div class="row"><span class="label">Plate Number:</span> {{ $vehicle->plate_number }}</div>
            <div class="row"><span class="label">Monthly Fuel Limit:</span> {{ $monthlyLimit }} liters</div>

            <h2>Yearly Budget Overview</h2>
            <div class="row"><span class="label">Yearly Budget:</span> ₱{{ number_format($yearlyBudget, 2) }}</div>
            <div class="row"><span class="label">Total Used (YTD):</span> ₱{{ number_format($yearlyBudget - $remainingBudget, 2) }}</div>
            <div class="row"><span class="label">Remaining Budget:</span> ₱{{ number_format($remainingBudget, 2) }}</div>
            <div class="row"><span class="label">Budget Used:</span> {{ $budgetUsedPercentage }}%</div>
            <div class="bar">
                <div class="bar-blue" style="width: {{ min(max($budgetUsedPercentage, 0), 100) }}%;"></div>
            </div>
            <div class="small">This budget bar reflects year-to-date fuel slip and maintenance costs.</div>

            <h2>Monthly Breakdown ({{ now()->year }})</h2>
            <table class="monthly-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-right">Liters Used</th>
                        <th class="text-right">Cost (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalLiters = 0;
                        $totalCost = 0;
                    @endphp
                    @foreach($monthlyData as $month)
                        @php
                            $totalLiters += $month['liters'];
                            $totalCost += $month['cost'];
                        @endphp
                        <tr>
                            <td>{{ $month['month'] }}</td>
                            <td class="text-right">{{ number_format($month['liters'], 2) }}</td>
                            <td class="text-right">₱{{ number_format($month['cost'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background: #f5f5f5; font-weight: 600;">
                        <td><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($totalLiters, 2) }}</strong></td>
                        <td class="text-right"><strong>₱{{ number_format($totalCost, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <h2 style="margin-top: 20px;">Summary Statistics</h2>
            <div class="row"><span class="label">Average Monthly Usage:</span> {{ number_format($totalLiters / 12, 2) }} liters</div>
            <div class="row"><span class="label">Average Monthly Cost:</span> ₱{{ number_format($totalCost / 12, 2) }}</div>
            @php
                $sorted = collect($monthlyData)->sortByDesc('liters');
                $highest = $sorted->first() ?? null;
            @endphp
            <div class="row"><span class="label">Highest Consumption Month:</span>
                {{ $highest['month'] ?? 'N/A' }} ({{ number_format($highest['liters'] ?? 0, 2) }} L)
            </div>
        @else
            <div class="row"><span class="label">Vehicle:</span> No vehicle assigned.</div>
        @endif
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: center; font-size: 11px; color: #666;">
        <p>This is an official yearly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>
</body>
</html>
