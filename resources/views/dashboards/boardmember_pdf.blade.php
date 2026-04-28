 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Boardmember Monthly Dashboard PDF</title>
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
        .card-value { font-size: 16px; font-weight: 700; color: #222; margin-top: 4px; }
        .card-sub   { font-size: 9.5px; color: #888; margin-top: 2px; }

        /* ── PROGRESS BARS ── */
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
            transition: width 0.3s ease;
        }
        .bar-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .bar-orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .bar-red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

        /* ── STATUS COLORS ── */
        .status-high   { color: #dc2626; font-weight: 600; }
        .status-medium { color: #f59e0b; font-weight: 600; }
        .status-low    { color: #059669; font-weight: 600; }

        /* ── ALERTS ── */
        .alerts-box {
            background: #fef3c7; border-left: 3px solid #f59e0b;
            border-radius: 4px; padding: 10px 14px;
            margin-bottom: 16px;
        }
        .alerts-box .label { color: #92400e; }
        .alerts-box ul { margin: 6px 0 0 16px; padding: 0; }
        .alerts-box li { color: #92400e; font-size: 10.5px; margin-bottom: 3px; }

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
            <td><span class="label">Period:</span> {{ $selectedMonthName ?? now()->format('F') }} {{ now()->year }}</td>
        </tr>
    </table>

    @if(!empty($alerts))
        <div class="alerts-box">
            <span class="label">⚠ Alerts:</span>
            <ul>
                @foreach($alerts as $alert)
                    <li>{{ $alert }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($vehicle)
        {{-- ── SUMMARY CARDS ── --}}
        <h2>Monthly Overview</h2>
        <table class="cards-table">
            <tr>
                <td class="card">
                    <div class="card-label">Budget Used</div>
                    <div class="card-value {{ $budgetUsedPercentage >= 80 ? 'status-high' : ($budgetUsedPercentage >= 50 ? 'status-medium' : 'status-low') }}">{{ $budgetUsedPercentage }}%</div>
                    <div class="card-sub">Year-to-date</div>
                </td>
                <td class="card">
                    <div class="card-label">Fuel Used</div>
                    <div class="card-value">{{ $monthlyLitersUsed }} L</div>
                    <div class="card-sub">of {{ $monthlyLimit }} L limit</div>
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

        {{-- ── BUDGET OVERVIEW ── --}}
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
        <div style="font-size: 10px; color: #64748b; margin-bottom: 16px;">Budget utilization: &#8369;{{ number_format($yearlyBudget - $remainingBudget, 2) }} of &#8369;{{ number_format($yearlyBudget, 2) }}</div>

        {{-- ── FUEL CONSUMPTION ── --}}
        <h2>Fuel Consumption ({{ $selectedMonthName ?? now()->format('F') }})</h2>
        @php
            $fuelPercent = $monthlyLimit > 0 ? round(($monthlyLitersUsed / $monthlyLimit) * 100, 2) : 0;
            if ($fuelPercent > 100) $fuelPercent = 100;
            if ($fuelPercent < 0) $fuelPercent = 0;
        @endphp

        <div class="bar-container">
            <div class="bar-fill {{ $fuelPercent >= 80 ? 'bar-red' : ($fuelPercent >= 50 ? 'bar-orange' : 'bar-blue') }}" style="width: {{ $fuelPercent }}%;"></div>
        </div>
        <div style="font-size: 10px; color: #64748b; margin-bottom: 16px;">
            {{ $monthlyLitersUsed }} liters used of {{ $monthlyLimit }} liters limit ({{ $fuelPercent }}%)
        </div>
    @else
        <div class="no-vehicle">
            <strong style="color: #dc2626;">No vehicle assigned.</strong>
            <p style="margin-top: 8px; font-size: 10px;">Please contact your administrator to assign a vehicle to your account.</p>
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This is a monthly report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>

</body>
</html>

