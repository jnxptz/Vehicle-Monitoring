<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Monthly Dashboard PDF</title>
    <link rel="stylesheet" href="{{ public_path('css/boardmember_pdf.css') }}">
    <style>
        .monthly-table { width:100%; border-collapse:collapse; margin-top:12px; }
        .monthly-table th { background:#1976d2; color:#fff; padding:8px; border:1px solid #1976d2; }
        .monthly-table td { padding:8px; border:1px solid #e0e0e0; }
        .monthly-table tbody tr:nth-child(even){ background:#f9f9f9; }
        .text-right { text-align:right; }
        .kpi-box { display:inline-block; background:#f5f5f5; padding:12px 20px; margin-right:20px; margin-bottom:12px; border-radius:4px; }
        .kpi-label { font-size:11px; color:#666; }
        .kpi-value { font-size:18px; font-weight:600; color:#1976d2; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom:6px;">
        <img src="{{ public_path('images/SP Seal.png') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
        <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
    </div>
    <h1>Board Member Monthly Dashboard</h1>
    <div class="meta">
        <div><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
        <div><span class="label">Month:</span> {{ $selectedMonthName }} {{ $year }}</div>
        @if($officeName)
            <div><span class="label">Office:</span> {{ $officeName }}</div>
        @endif
    </div>

    <h2>Monthly Summary</h2>
    <div>
        <div class="kpi-box">
            <div class="kpi-label">Total Liters Used</div>
            <div class="kpi-value">{{ number_format($totalMonthlyLiters, 2) }} L</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-label">Total Cost</div>
            <div class="kpi-value">₱{{ number_format($totalMonthlyCost, 2) }}</div>
        </div>
    </div>

    <h2 style="margin-top:20px;">Boardmember Details ({{ $selectedMonthName }} {{ $year }})</h2>
    <table class="monthly-table" style="font-size:11px;">
        <thead>
            <tr>
                <th>#</th>
                <th>Boardmember</th>
                <th class="text-right">Monthly Liters</th>
                <th class="text-right">Monthly Cost</th>
                <th class="text-right">Yearly Budget</th>
                <th class="text-right">Yearly Used</th>
                <th class="text-right">Yearly Remaining</th>
                <th class="text-right">Used %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['user']->name }}</td>
                    <td class="text-right">{{ number_format($row['monthlyLitersUsed'], 2) }}</td>
                    <td class="text-right">₱{{ number_format($row['monthlyCostUsed'], 2) }}</td>
                    <td class="text-right">₱{{ number_format($row['yearlyBudget'], 2) }}</td>
                    <td class="text-right">₱{{ number_format($row['totalUsed'], 2) }}</td>
                    <td class="text-right">₱{{ number_format($row['remainingBudget'], 2) }}</td>
                    <td class="text-right">{{ $row['budgetUsedPercentage'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px;">No boardmembers found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($rows->count() > 0)
        <h2 style="margin-top:20px;">Vehicle Breakdown</h2>
        @foreach($rows as $row)
            @if(count($row['vehicles']) > 0)
                <h3>{{ $row['user']->name }}</h3>
                <table class="monthly-table" style="font-size:10px;">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th class="text-right">Plate Number</th>
                            <th class="text-right">Fuel Cost</th>
                            <th class="text-right">Maintenance Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row['vehicles'] as $vehicle)
                            <tr>
                                <td>{{ $vehicle['vehicle']->vehicle_name }}</td>
                                <td class="text-right">{{ $vehicle['vehicle']->plate_number }}</td>
                                <td class="text-right">₱{{ number_format($vehicle['fuelSlipCost'], 2) }}</td>
                                <td class="text-right">₱{{ number_format($vehicle['maintenanceCost'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

    <div style="margin-top:30px; padding-top:20px; border-top:1px solid #e0e0e0; text-align:center; font-size:11px; color:#666;">
        <p>This is a monthly fleet report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>
</body>
</html>
