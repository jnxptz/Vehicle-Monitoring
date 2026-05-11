<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Fuel Slip Recorded</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .header {
            background: #2563eb;
            color: #fff;
            padding: 20px 24px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .content {
            padding: 24px;
            color: #334155;
            font-size: 14px;
            line-height: 1.6;
        }
        .alert-box {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .details-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .details-table td:first-child {
            font-weight: 600;
            color: #1e293b;
            width: 40%;
        }
        .footer {
            background: #f8fafc;
            padding: 14px 24px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Vehicle Monitoring System</h2>
        </div>
        <div class="content">
            <div class="alert-box">
                <strong>New Fuel Slip Recorded</strong><br>
                A fuel slip has been created for your vehicle.
            </div>

            <p>Hello {{ $user?->name ?? 'Board Member' }},</p>
            <p>An administrator has recorded a new fuel slip for the vehicle assigned to you. Here are the details:</p>

            <table class="details-table">
                <tr>
                    <td>Vehicle Plate</td>
                    <td>{{ $vehicle?->plate_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $fuelSlip->date->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td>Fuel (Liters)</td>
                    <td>{{ number_format($fuelSlip->liters, 2) }} L</td>
                </tr>
                <tr>
                    <td>Total Cost</td>
                    <td>P{{ number_format($fuelSlip->total_cost, 2) }}</td>
                </tr>
                <tr>
                    <td>KM Reading</td>
                    <td>{{ $fuelSlip->km_reading ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Official Business</td>
                    <td>{{ $fuelSlip->is_official_business ? 'Yes' : 'No' }}</td>
                </tr>
            </table>

            <p style="margin-top: 20px;">Please log in to the <a href="{{ url('/') }}">Vehicle Monitoring System</a> to view more details.</p>
        </div>
        <div class="footer">
            This notification was generated on {{ $timestamp }}<br>
            BM Vehicle Monitoring System
        </div>
    </div>
</body>
</html>
