<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance PDF</title>
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
        .photo { margin-top:14px; }
        .photo .label { margin-bottom:6px; font-weight:700; }
        .photo img { max-width:100%; height:auto; border:1px solid #e0e0e0; border-radius:6px; }

        .footer-bar { position:fixed; left:0; right:0; bottom:0; background:#b71c1c; color:#fff; padding:10px 16px; text-align:center; font-size:11px; }
    </style>
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
            <div class="doc-title">MAINTENANCE REPORT</div>
        </div>
    </div>

    <div class="meta">
        <div style="flex:1;">
            <div class="to-line"><strong>Vehicle (Plate Number):</strong> {{ $maintenance->vehicle?->plate_number ?? 'N/A' }}</div>
            <div class="to-line"><strong>Office:</strong> {{ $maintenance->vehicle?->bm?->name ?? 'N/A' }}</div>
        </div>
        <div class="control">
            <div><strong>Call of No.:</strong> {{ $maintenance->call_of_no }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($maintenance->date)->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="card">
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:34%;">Item</th>
                    <th style="text-align:right;">Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align:middle;"><strong>Maintenance Type</strong></td>
                    <td style="text-align:right; vertical-align:middle;">{{ ucfirst($maintenance->maintenance_type ?? 'preventive') }}</td>
                </tr>
                <tr>
                    <td style="vertical-align:middle;"><strong>Odometer KM</strong></td>
                    <td style="text-align:right; vertical-align:middle;">{{ $maintenance->maintenance_km ?? '—' }}</td>
                </tr>
                <tr>
                    <td style="vertical-align:middle;"><strong>Operation(s) Done</strong></td>
                    <td style="text-align:left; vertical-align:middle;">{{ $maintenance->operation }}</td>
                </tr>
                <tr>
                    <td style="vertical-align:middle;"><strong>Cost</strong></td>
                    <td style="text-align:right; vertical-align:middle;">₱{{ number_format((float) $maintenance->cost, 2) }}</td>
                </tr>
                <tr>
                    <td style="vertical-align:middle;"><strong>Conduct</strong></td>
                    <td style="text-align:left; vertical-align:middle;">{{ $maintenance->conduct }}</td>
                </tr>
            </tbody>
        </table>

        @if($maintenance->photo)
            @php
                $photoPath = storage_path('app/public/' . $maintenance->photo);
                $photoDataUri = null;
                if (is_file($photoPath)) {
                    $mime = mime_content_type($photoPath) ?: 'image/jpeg';
                    $photoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
                }
            @endphp

            @if($photoDataUri)
                <div class="photo">
                    <div class="label">Photo</div>
                    <img src="{{ $photoDataUri }}" alt="Maintenance photo">
                </div>
            @endif
        @endif
    </div>

    <div style="margin-top:28px;">
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

    <div class="footer-bar">LA UNION: Agkaysa! • (072) 682-2083 • sangguniangpanlalawiganlaunion@gmail.com • www.launion.gov.ph</div>
</body>
</html>
