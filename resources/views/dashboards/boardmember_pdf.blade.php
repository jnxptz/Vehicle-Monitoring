<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boardmember Dashboard PDF</title>
    <link rel="stylesheet" href="{{ public_path('css/boardmember_pdf.css') }}">
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
            <div class="doc-title">Boardmember Dashboard</div>
        </div>
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

