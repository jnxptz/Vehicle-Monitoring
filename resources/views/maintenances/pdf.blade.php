<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Maintenance PDF</title>
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
        .photo { margin-top: 14px; }
        .photo .label { margin-bottom: 6px; }
        .photo img { max-width: 100%; height: auto; border: 1px solid #e0e0e0; border-radius: 6px; }
        .footer { margin-top: 14px; font-size: 11px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div style="text-align:center; margin-bottom:6px;">
        <img src="{{ public_path('images/SP Seal.png') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
        <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="Logo" style="height:64px; margin-bottom:4px;">
    </div>
    <h1>Maintenance Record</h1>

    <div class="meta">
        <div class="row"><span class="label">Call of No:</span> {{ $maintenance->call_of_no }}</div>
        <div class="row"><span class="label">Generated:</span> {{ now()->format('F d, Y h:i A') }}</div>
    </div>

    <div class="box">
        <table>
            <tr>
                <td class="key">Vehicle (Plate Number)</td>
                <td>{{ $maintenance->vehicle?->plate_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="key">Board Member</td>
                <td>{{ $maintenance->vehicle?->bm?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="key">Maintenance Type</td>
                <td>{{ ucfirst($maintenance->maintenance_type ?? 'preventive') }}</td>
            </tr>
            <tr>
                <td class="key">Odometer KM</td>
                <td>{{ $maintenance->maintenance_km ?? '—' }}</td>
            </tr>
            <tr>
                <td class="key">Operation(s) Done</td>
                <td>{{ $maintenance->operation }}</td>
            </tr>
            <tr>
                <td class="key">Cost</td>
                <td>₱{{ number_format((float) $maintenance->cost, 2) }}</td>
            </tr>
            <tr>
                <td class="key">Conduct</td>
                <td>{{ $maintenance->conduct }}</td>
            </tr>
            <tr>
                <td class="key">Date</td>
                <td>{{ \Carbon\Carbon::parse($maintenance->date)->format('F d, Y') }}</td>
            </tr>
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

    <div class="footer">
        This document is system-generated.
    </div>
</body>
</html>
