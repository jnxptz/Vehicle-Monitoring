<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Adjustment Notification</title>
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
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .alert-increase {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 1px solid #86efac;
        }
        .alert-decrease {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            border: 1px solid #fdba74;
        }
        .alert-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .alert-increase .alert-title {
            color: #166534;
        }
        .alert-decrease .alert-title {
            color: #9a3412;
        }
        .budget-details {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .budget-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .budget-row:last-child {
            border-bottom: none;
        }
        .budget-label {
            color: #64748b;
            font-size: 14px;
        }
        .budget-value {
            font-weight: 600;
            font-size: 14px;
        }
        .budget-value.old {
            color: #64748b;
        }
        .budget-value.new {
            color: #1e40af;
            font-size: 16px;
        }
        .budget-value.adjustment {
            font-size: 16px;
        }
        .adjustment-positive {
            color: #16a34a;
        }
        .adjustment-negative {
            color: #dc2626;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Vehicle Monitoring System</h1>
            <p>Sangguniang Panlalawigan - Provincial Government of La Union</p>
        </div>

        <div class="content">
            <p style="font-size: 16px; margin-bottom: 20px;">Dear {{ $user->name }},</p>
            
            <p style="margin-bottom: 25px;">We would like to inform you that your yearly budget has been adjusted. Please see the details below:</p>

            <div class="alert-box {{ $type === 'increase' ? 'alert-increase' : 'alert-decrease' }}">
                <div class="alert-title">
                    @if($type === 'increase')
                        ✅ Budget Increased
                    @else
                        ⚠️ Budget Decreased
                    @endif
                </div>
                <p style="margin: 0; font-size: 14px; color: #374151;">
                    Your budget has been {{ $type === 'increase' ? 'increased' : 'decreased' }} by 
                    <strong>₱{{ number_format(abs($adjustmentAmount), 2) }}</strong>
                </p>
            </div>

            <div class="budget-details">
                <div class="budget-row">
                    <span class="budget-label">Previous Budget:</span>
                    <span class="budget-value old">₱{{ number_format($oldBudget, 2) }}</span>
                </div>
                <div class="budget-row">
                    <span class="budget-label">Adjustment Amount:</span>
                    <span class="budget-value adjustment {{ $adjustmentAmount > 0 ? 'adjustment-positive' : 'adjustment-negative' }}">
                        {{ $adjustmentAmount > 0 ? '+' : '-' }}₱{{ number_format(abs($adjustmentAmount), 2) }}
                    </span>
                </div>
                <div class="budget-row">
                    <span class="budget-label">New Budget:</span>
                    <span class="budget-value new">₱{{ number_format($newBudget, 2) }}</span>
                </div>
            </div>

            <div class="info-box">
                <p>
                    <strong>Note:</strong> This adjustment was made by the administrator. If you have any questions or concerns regarding this budget change, please contact the Vehicle Monitoring System administrator.
                </p>
            </div>

            <p style="margin-top: 25px; font-size: 14px; color: #64748b;">
                Date of Adjustment: {{ $timestamp }}
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
