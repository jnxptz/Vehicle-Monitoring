<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fuel/Oil Slip</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; margin:0; padding:24px 28px; }
        .header { text-align:center; margin-bottom:6px; }
        .header .logos { display:flex; justify-content:space-between; align-items:center; }
        .header .logos img { height:64px; }
        .gov-title { text-transform:uppercase; font-weight:700; font-size:14px; color:#0b5aa8; margin:6px 0 2px; }
        .sub-title { font-size:13px; color:#0b5aa8; margin-bottom:6px; }
        .doc-title { font-weight:700; font-size:18px; margin:10px 0; }
        .meta { display:flex; justify-content:space-between; margin-bottom:10px; font-size:13px; }
        .to-line { margin-top:6px; }
        .control { text-align:right; }

        .card { border:1px solid #dcdcdc; padding:10px; border-radius:6px; }
        .items-table { width:100%; border-collapse:collapse; margin-top:8px; }
        .items-table th, .items-table td { border:1px solid #bfbfbf; padding:8px 6px; text-align:left; font-size:12px; }
        .items-table th { background:#f5f5f5; font-weight:700; }

        .details { width:100%; margin-top:10px; }
        .sign-row { display:flex; justify-content:space-between; margin-top:28px; }
        .sign-box { width:45%; text-align:center; }
        .sign-line { border-top:1px solid #000; margin-top:44px; padding-top:6px; font-weight:700; }

        .km-reading { position:fixed; right:32px; bottom:80px; color:#d32f2f; font-weight:700; }

        .footer-bar { position:fixed; left:0; right:0; bottom:0; background:#b71c1c; color:#fff; padding:10px 16px; text-align:center; font-size:11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logos">
            <div style="flex:1; text-align:left;"><img src="{{ public_path('images/SP Seal.png') }}" alt="left-logo"></div>
            <div style="flex:2; text-align:center;">
                <div class="gov-title">Province of La Union</div>
                <div class="sub-title">Office of the Sangguniang Panlalawigan</div>
                <div class="doc-title">FUEL/OIL SLIP</div>
            </div>
            <div style="flex:1; text-align:right;"><img src="{{ public_path('images/bmvslogo.png') }}" alt="right-logo"></div>
        </div>
    </div>

    <div class="meta">
        <div style="flex:1;">
            <div class="to-line"><strong>To:</strong> {{ $fuelSlip->user?->office?->name ?? 'N/A' }}</div>
            <div class="to-line"><strong>Please issue the following item to:</strong> <strong>{{ strtoupper($fuelSlip->driver) }}</strong></div>
            <div style="margin-top:6px;"><strong>With PGLU/Provincial Plate No.:</strong> {{ $fuelSlip->plate_number }}</div>
        </div>
        <div class="control">
            <div><strong>Control No.:</strong> {{ $fuelSlip->control_number }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($fuelSlip->date)->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="card">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:18%;">Quantity</th>
                    <th style="width:12%;">Unit</th>
                    <th style="width:34%;">Item</th>
                    <th style="width:18%; text-align:right;">Unit Cost</th>
                    <th style="width:18%; text-align:right;">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align:middle;">{{ number_format((float)$fuelSlip->liters, 2) }}</td>
                    <td style="vertical-align:middle;">LITERS</td>
                    <td style="vertical-align:middle;">DIESEL</td>
                    <td style="text-align:right; vertical-align:middle;">{{ '₱' . number_format((float)$fuelSlip->cost / max(1, (float)$fuelSlip->liters), 2) }}</td>
                    <td style="text-align:right; vertical-align:middle;">{{ '₱' . number_format((float)$fuelSlip->cost, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="details">
        <div style="margin-top:14px;"><strong>Purpose:</strong></div>
        <div style="margin-top:6px; margin-bottom:4px;">
            <div style="border-bottom:1px solid #000; height:14px;"></div>
        </div>
        <div style="margin-bottom:4px;">
            <div style="border-bottom:1px solid #000; height:14px;"></div>
        </div>
        <div style="margin-bottom:4px;">
            <div style="border-bottom:1px solid #000; height:14px;"></div>
        </div>

        <div class="sign-row" style="margin-top:28px;">
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:50%; padding-right:20px; padding-bottom:12px; text-align:left; border:none;">
                        <strong>Prepared by:</strong>
                    </td>
                    <td style="width:50%; padding-left:2px; padding-bottom:12px; text-align:right; border:none;">
                        <strong>Approved by:</strong>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; padding-right:20px; padding-bottom:6px; text-align:left; border:none; font-size:12px;">
                        SHERLY P. RABUT
                    </td>
                    <td style="width:50%; padding-left:20px; padding-bottom:6px; text-align:right; border:none; font-size:12px;">
                        JANE T. FLORES, Ph.D.
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; padding-right:50px; padding-bottom:2px; text-align:center; border:none;">
                        <div style="border-top:2px solid #000;"></div>
                    </td>
                    <td style="width:50%; padding-left:50px; padding-bottom:2px; text-align:center; border:none;">
                        <div style="border-top:2px solid #000;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; padding-right:20px; padding-top:2px; text-align:left; border:none; font-size:11px;">
                        <small>Laborer I</small>
                    </td>
                    <td style="width:50%; padding-left:20px; padding-top:2px; text-align:right; border:none; font-size:11px;">
                        <small>Secretary to the Sangguniang</small>
                    </td>
                </tr>
            </table>
        </div>

        <div style="display:flex; justify-content:space-between; margin-top:18px;">
            <div><strong>Accept by:</strong> ____________________<br><strong>Invoice No.:</strong> ____________________</div>
        </div>
    </div>

    <div class="km-reading">KM READING: {{ $fuelSlip->km_reading }}</div>

    <div class="footer-bar">LA UNION: Agkaysa! • (072) 682-2083 • sangguniangpanlalawiganlaunion@gmail.com • www.launion.gov.ph</div>
</body>
</html>
