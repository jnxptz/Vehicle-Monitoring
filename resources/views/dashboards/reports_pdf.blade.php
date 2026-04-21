<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reports PDF</title>
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
        .meta-table td { padding: 7px 12px; font-size: 10.5px; color: #444; width: 50%; }
        .label { font-weight: 600; color: #1976d2; }

        /* ── SECTION TITLES ── */
        h2 {
            font-size: 11.5px; font-weight: 700; color: #1976d2;
            margin: 16px 0 8px; padding-bottom: 4px;
            border-bottom: 1px solid #d0d9e8;
            text-transform: uppercase; letter-spacing: 0.4px;
        }

        /* ── DATA TABLES ── */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 10.5px; }
        .data-table th {
            background: #1976d2; color: #fff;
            padding: 7px 10px; text-align: left;
            font-weight: 600; font-size: 10px; letter-spacing: 0.3px;
        }
        .data-table th.tr, .data-table td.tr { text-align: right; }
        .data-table th.tc, .data-table td.tc { text-align: center; }
        .data-table td { padding: 6px 10px; border-bottom: 1px solid #e8edf4; color: #333; }
        .data-table tbody tr:nth-child(even) { background: #f7f9fd; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px; padding-top: 12px;
            border-top: 1px solid #e0e0e0;
            text-align: center; font-size: 9.5px; color: #999; line-height: 1.6;
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
                <div class="doc-title">Boardmember Comparison Report</div>
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
            <td><span class="label">Period:</span> {{ $periodLabel }}</td>
        </tr>
    </table>

    {{-- ── COMPARISON TABLE ── --}}
    <h2>Boardmember Comparison ({{ $periodLabel }})</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th class="tc" style="width:40px;">#</th>
                <th>Boardmember</th>
                <th>Office</th>
                <th class="tr">Fuel Cost</th>
                <th class="tr">Maintenance</th>
                <th class="tr">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sortedStats = collect($boardmemberStats)->sortByDesc(function($item) {
                    return ($item['fuelSlipCost'] ?? 0) + ($item['maintenanceCost'] ?? 0);
                });
                $rank = 1;
            @endphp
            @forelse($sortedStats as $stats)
                @php $total = ($stats['fuelSlipCost'] ?? 0) + ($stats['maintenanceCost'] ?? 0); @endphp
                <tr>
                    <td class="tc">#{{ $rank++ }}</td>
                    <td>{{ $stats['name'] }}</td>
                    <td>{{ $stats['office'] ?? 'N/A' }}</td>
                    <td class="tr">&#8369;{{ number_format($stats['fuelSlipCost'] ?? 0, 2) }}</td>
                    <td class="tr">&#8369;{{ number_format($stats['maintenanceCost'] ?? 0, 2) }}</td>
                    <td class="tr"><strong>&#8369;{{ number_format($total, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="tc" style="padding:16px; font-style:italic; color:#999;">
                        No data available for the selected period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p>This is a comparison report generated from the BM Vehicle Monitoring System.</p>
        <p>Report generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</p>
    </div>

</body>
</html>