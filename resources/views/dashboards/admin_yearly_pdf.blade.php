<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Yearly Dashboard PDF</title>
    <link rel="stylesheet" href="{{ public_path('css/boardmember_pdf.css') }}">
    <style>
        .monthly-table { width:100%; border-collapse:collapse; margin-top:12px; }
        .monthly-table th { background:#1976d2; color:#fff; padding:8px; border:1px solid #1976d2; }
        .monthly-table td { padding:8px; border:1px solid #e0e0e0; }
        .monthly-table tbody tr:nth-child(even){ background:#f9f9f9; }
        .text-right { text-align:right; }
    </style>
</head>
<body>
    <div class="header" style="position:relative; padding-top:6px; margin-bottom:6px;">
        <div class="logo-left" style="position:absolute; left:14px; top:0;">
            <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="left-logo" style="width:48px; height:auto;">
        </div>
        <div class="logo-right" style="position:absolute; right:14px; top:0;">
            <img src="{{ public_path('images/Bagong-Pilipinas.png') }}" alt="right-logo" style="width:48px; height:auto;">
        </div>
        <div style="text-align:center; max-width:720px; margin:0 auto;">
            <div class="gov-title">Province of La Union</div>
            <div class="sub-title">Office of the Sangguniang Panlalawigan</div>
            <div class="doc-title">Board Member Yearly Report</div>
        </div>
    </div>
    <h1>Board Member Dashboard (Fleet-wide)</h1>
    <div class="meta">
        <div><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
        <div><span class="label">Year:</span> {{ $year }}</div>
    </div>

    <h2>Monthly Breakdown ({{ $year }})</h2>
    <table class="monthly-table">
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Liters Used</th>
                <th class="text-right">Cost (₱)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $m)
                <tr>
                    <td>{{ $m['month'] }}</td>
                    <td class="text-right">{{ number_format($m['liters'], 2) }}</td>
                    <td class="text-right">₱{{ number_format($m['cost'], 2) }}</td>
                </tr>
            @endforeach
            <tr style="background:#f5f5f5; font-weight:600;">
                <td><strong>Total</strong></td>
                <td class="text-right"><strong>{{ number_format($totalLiters, 2) }}</strong></td>
                <td class="text-right"><strong>₱{{ number_format($totalCost, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h2 style="margin-top:20px;">Highlights</h2>
    <div class="row"><span class="label">Highest Consumption Month:</span> {{ $highest['month'] ?? 'N/A' }} ({{ number_format($highest['liters'] ?? 0, 2) }} L)</div>

    <h3 style="margin-top:12px;">Top Vehicles (by liters, top 5)</h3>
    @if($topVehicles && $topVehicles->count())
        <table class="monthly-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vehicle</th>
                    <th class="text-right">Liters</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topVehicles as $tv)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tv['vehicle']->plate_number }} — {{ $tv['vehicle']->bm?->name ?? '—' }}</td>
                        <td class="text-right">{{ number_format($tv['liters'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No vehicle fuel data available.</p>
    @endif

    <h2 style="margin-top:20px;">Boardmember Budget Analysis</h2>
<table class="monthly-table" style="font-size:11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Boardmember</th>
            <th>Vehicle</th>
            <th class="text-right">Yearly Budget</th>
            <th class="text-right">Used</th>
            <th class="text-right">Remaining</th>
            <th class="text-right">Used %</th>
            <th>Recommendation</th>
            <th class="text-right">Suggested Budget</th>
        </tr>
    </thead>
    <tbody>
        @foreach($boardmembersData as $bm)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $bm['user']->name }}</td>
                <td>{{ $bm['vehicle']?->plate_number ?? '—' }}</td>
                <td class="text-right">{{ number_format($bm['yearlyBudget'], 2) }}</td>
                <td class="text-right">{{ number_format($bm['totalUsed'], 2) }}</td>
                <td class="text-right">{{ number_format($bm['remaining'], 2) }}</td>
                <td class="text-right">{{ $bm['usedPercent'] }}%</td>
                <td>{{ $bm['status'] }}</td>
                <td class="text-right">{{ number_format($bm['suggestedBudget'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


    <div style="margin-top:30px; padding-top:20px; border-top:1px solid #e0e0e0; text-align:center; font-size:11px; color:#666;">
        <p>This is a fleet-wide yearly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>
</body>
</html>
