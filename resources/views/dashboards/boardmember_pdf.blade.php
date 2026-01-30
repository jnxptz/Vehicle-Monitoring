<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boardmember Dashboard PDF</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { color: #1976d2; text-align: center; margin: 0 0 8px; }
        h2 { color: #1976d2; margin: 18px 0 8px; font-size: 14px; }
        .meta { text-align: center; color: #555; margin-bottom: 14px; }
        .box { border: 1px solid #e0e0e0; border-top: 4px solid #FF9B00; padding: 14px; border-radius: 6px; }
        .row { margin: 6px 0; }
        .label { font-weight: 700; }
        .alerts { background: #ffebee; border: 1px solid #d32f2f; padding: 10px 12px; border-radius: 6px; }
        .alerts ul { margin: 6px 0 0; padding-left: 18px; }
        .bar { height: 14px; background: #eee; border-radius: 10px; overflow: hidden; }
        .bar > div { height: 100%; }
        .bar-blue { background: #1976d2; }
        .bar-orange { background: #FF9B00; }
        .small { color: #666; font-size: 11px; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom:6px;">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height:32px; margin-bottom:4px;">
    </div>
    <h1>Boardmember Dashboard</h1>
    <div class="meta">
        <div><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
        <div><span class="label">Month:</span> {{ $selectedMonthName ?? '' }} ({{ now()->year }})</div>
    </div>

    <div class="box">
        @if(!empty($alerts))
            <div class="alerts">
                <div class="label">Alerts</div>
                <ul>
                    @foreach($alerts as $alert)
                        <li>{{ $alert }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($vehicle)
            <h2>Vehicle</h2>
            <div class="row"><span class="label">Plate Number:</span> {{ $vehicle->plate_number }}</div>
            <div class="row"><span class="label">Monthly Fuel Limit:</span> {{ $monthlyLimit }} liters</div>

            <h2>Budget Overview</h2>
            <div class="row"><span class="label">Yearly Budget:</span> ₱{{ number_format($yearlyBudget, 2) }}</div>
            <div class="row"><span class="label">Remaining Budget:</span> ₱{{ number_format($remainingBudget, 2) }}</div>
            <div class="row"><span class="label">Budget Used:</span> {{ $budgetUsedPercentage }}%</div>
            <div class="bar">
                <div class="bar-blue" style="width: {{ min(max($budgetUsedPercentage, 0), 100) }}%;"></div>
            </div>
            <div class="small">This budget bar reflects year-to-date fuel slip costs.</div>

            <h2>Fuel Consumption ({{ $selectedMonthName }})</h2>
            <div class="row"><span class="label">Liters Used:</span> {{ $monthlyLitersUsed }} liters</div>
            @php
                $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;
                if ($fuelPercent > 100) $fuelPercent = 100;
                if ($fuelPercent < 0) $fuelPercent = 0;
            @endphp
            <div class="bar">
                <div class="bar-orange" style="width: {{ $fuelPercent }}%;"></div>
            </div>
            <div class="small">This fuel bar reflects liters used in the selected month.</div>
        @else
            <div class="row"><span class="label">Vehicle:</span> No vehicle assigned.</div>
        @endif
    </div>
</body>
</html>

