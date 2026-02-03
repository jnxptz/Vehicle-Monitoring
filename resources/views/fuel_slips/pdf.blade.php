<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fuel Slip PDF</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { color: #1976d2; text-align: center; margin: 0 0 6px; font-size: 18px; }
        .meta { text-align: center; color: #555; margin-bottom: 14px; }
        .meta .row { margin: 2px 0; }
        .label { font-weight: 700; }
        .box { border: 1px solid #e0e0e0; border-top: 4px solid #FF9B00; padding: 14px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        td { padding: 8px 10px; vertical-align: top; border-bottom: 1px solid #eee; }
        td.key { width: 34%; background: #fafafa; font-weight: 700; }
        .footer { margin-top: 14px; font-size: 11px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom:6px;">
        <img src="{{ public_path('images/SP Seal.png') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
        <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
    </div>
    <h1>Fuel Slip</h1>

    <div class="meta">
        <div class="row"><span class="label">Control No:</span> {{ $fuelSlip->control_number }}</div>
        <div class="row"><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="key">Vehicle Name</td>
                <td>{{ $fuelSlip->vehicle?->vehicle_name ?? $fuelSlip->vehicle_name }}</td>
            </tr>
            <tr>
                <td class="key">Plate Number</td>
                <td>{{ $fuelSlip->vehicle?->plate_number ?? $fuelSlip->plate_number }}</td>
            </tr>
            <tr>
                <td class="key">Driver</td>
                <td>{{ $fuelSlip->driver }}</td>
            </tr>
            <tr>
                <td class="key">Date</td>
                <td>{{ \Carbon\Carbon::parse($fuelSlip->date)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td class="key">Liters</td>
                <td>{{ number_format((float) $fuelSlip->liters, 2) }}</td>
            </tr>
            <tr>
                <td class="key">Cost</td>
                <td>₱{{ number_format((float) $fuelSlip->cost, 2) }}</td>
            </tr>
            <tr>
                <td class="key">KM Reading</td>
                <td>{{ $fuelSlip->km_reading }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This document is system-generated.
    </div>
</body>
</html>
