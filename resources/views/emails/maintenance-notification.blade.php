<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .alert-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 10px;
        }
        .vehicle-info {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .vehicle-info h3 {
            margin: 0 0 15px 0;
            color: #1e293b;
            font-size: 16px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #64748b;
            font-size: 13px;
        }
        .info-value {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
        }
        .maintenance-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .maintenance-details h3 {
            margin: 0 0 15px 0;
            color: #1e293b;
            font-size: 16px;
        }
        .cost-box {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
        }
        .cost-label {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 5px;
        }
        .cost-value {
            font-size: 24px;
            font-weight: 700;
            color: #b45309;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 25px;
        }
        .info-box p {
            margin: 0;
            color: #1e40af;
            font-size: 13px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
        .footer strong {
            color: #1e293b;
        }
        .maintenance-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .type-preventive {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }
        .type-repair {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Vehicle Monitoring System</h1>
            <p>Sangguniang Panlalawigan - Provincial Government of La Union</p>
        </div>

        <div class="content">
            <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $user?->name ?? 'Boardmember' }},</p>
            
            <p style="margin-bottom: 25px;">A new maintenance record has been created for your assigned vehicle. Please see the details below:</p>

            <div class="alert-box">
                <div class="alert-title">
                    🔧 Maintenance Record Created
                </div>
                <p style="margin: 0; font-size: 14px; color: #374151;">
                    Control Number: <strong>{{ $maintenance->call_of_no }}</strong>
                </p>
            </div>

            <div class="vehicle-info">
                <h3>🚗 Vehicle Information</h3>
                <div class="info-row">
                    <span class="info-label">Plate Number:</span>
                    <span class="info-value">{{ $vehicle?->plate_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vehicle Name:</span>
                    <span class="info-value">{{ $vehicle?->vehicle_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">KM Reading:</span>
                    <span class="info-value">{{ number_format($maintenance->maintenance_km) }} km</span>
                </div>
            </div>

            <div class="maintenance-details">
                <h3>🔧 Maintenance Details</h3>
                <div class="info-row">
                    <span class="info-label">Maintenance Type:</span>
                    <span class="info-value">
                        <span class="maintenance-type type-{{ $maintenance->maintenance_type }}">
                            {{ ucfirst($maintenance->maintenance_type) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($maintenance->date)->format('F d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Conducted By:</span>
                    <span class="info-value">{{ $maintenance->conduct }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Prepared By:</span>
                    <span class="info-value">{{ $maintenance->prepared_by_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Approved By:</span>
                    <span class="info-value">{{ $maintenance->approved_by_name ?? 'N/A' }}</span>
                </div>
                @if($maintenance->operation)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                    <span class="info-label" style="display: block; margin-bottom: 8px;">Operation Details:</span>
                    <p style="margin: 0; font-size: 13px; color: #374151; line-height: 1.6;">{{ $maintenance->operation }}</p>
                </div>
                @endif
            </div>

            <div class="cost-box">
                <div class="cost-label">Maintenance Cost</div>
                <div class="cost-value">₱{{ number_format($maintenance->cost, 2) }}</div>
            </div>

            <div class="info-box">
                <p>
                    <strong>Note:</strong> This maintenance cost will be deducted from your yearly budget. Please ensure you have sufficient budget remaining for future maintenance needs.
                </p>
            </div>

            <p style="margin-top: 25px; font-size: 14px; color: #64748b;">
                Recorded on: {{ $timestamp }}
            </p>
        </div>

        <div class="footer">
            <p><strong>Vehicle Monitoring System</strong></p>
            <p>Sangguniang Panlalawigan - Provincial Government of La Union</p>
            <p style="margin-top: 10px; font-size: 11px; color: #94a3b8;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
