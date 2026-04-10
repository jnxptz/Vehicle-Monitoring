<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preventive Maintenance Due</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
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
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        .alert-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .alert-title {
            font-size: 20px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 10px;
        }
        .alert-message {
            font-size: 14px;
            color: #78350f;
            margin: 0;
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
            padding: 10px 0;
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
        .info-value.danger {
            color: #dc2626;
            font-size: 16px;
        }
        .info-value.warning {
            color: #f59e0b;
        }
        .km-comparison {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 25px 0;
            padding: 20px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-radius: 8px;
            border: 2px solid #fca5a5;
        }
        .km-box {
            text-align: center;
        }
        .km-label {
            font-size: 11px;
            color: #7f1d1d;
            margin-bottom: 5px;
        }
        .km-value {
            font-size: 20px;
            font-weight: 700;
        }
        .km-current {
            color: #dc2626;
        }
        .km-due {
            color: #f59e0b;
        }
        .arrow {
            font-size: 24px;
            color: #dc2626;
        }
        .action-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .action-box h3 {
            margin: 0 0 15px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .action-box ul {
            margin: 0;
            padding-left: 20px;
            color: #374151;
            font-size: 14px;
            line-height: 1.8;
        }
        .action-box li {
            margin-bottom: 5px;
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
        .urgent-note {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 25px;
        }
        .urgent-note p {
            margin: 0;
            color: #991b1b;
            font-size: 13px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ PREVENTIVE MAINTENANCE DUE</h1>
            <p>Vehicle Monitoring System</p>
        </div>

        <div class="content">
            <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $user?->name ?? 'Boardmember' }},</p>
            
            <div class="alert-box">
                <div class="alert-icon">🔧</div>
                <div class="alert-title">Maintenance Overdue</div>
                <p class="alert-message">
                    Your vehicle has exceeded the preventive maintenance threshold. Please schedule maintenance immediately to ensure vehicle safety and performance.
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
                    <span class="info-label">Last Maintenance Type:</span>
                    <span class="info-value warning">{{ ucfirst($lastMaintenanceType) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Last Maintenance KM:</span>
                    <span class="info-value">{{ number_format($lastMaintenanceKm) }} km</span>
                </div>
            </div>

            <div class="km-comparison">
                <div class="km-box">
                    <div class="km-label">CURRENT KM</div>
                    <div class="km-value km-current">{{ number_format($currentKm) }}</div>
                </div>
                <div class="arrow">➜</div>
                <div class="km-box">
                    <div class="km-label">NEXT DUE KM</div>
                    <div class="km-value km-due">{{ number_format($nextDueKm) }}</div>
                </div>
            </div>

            <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin-bottom: 25px; text-align: center;">
                <span style="font-size: 14px; color: #92400e;">
                    <strong>Excess KM:</strong> {{ number_format($currentKm - $nextDueKm) }} km over threshold
                </span>
            </div>

            <div class="action-box">
                <h3>✅ Required Actions</h3>
                <ul>
                    <li>Schedule preventive maintenance immediately</li>
                    <li>Contact your preferred service center</li>
                    <li>Update maintenance records after service</li>
                    <li>Ensure vehicle safety before continued use</li>
                </ul>
            </div>

            <div class="urgent-note">
                <p>
                    ⚠️ <strong>Important:</strong> Continued operation without preventive maintenance may result in vehicle damage and increased repair costs. Please prioritize this maintenance.
                </p>
            </div>

            <p style="margin-top: 25px; font-size: 14px; color: #64748b;">
                Notification sent: {{ $timestamp }}
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
