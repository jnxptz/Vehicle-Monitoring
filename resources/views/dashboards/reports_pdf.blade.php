<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Reports PDF</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        .header {
            position: relative;
            padding-top: 6px;
            margin-bottom: 6px;
        }
        
        .logo-left {
            position: absolute;
            left: 120px;
            top: 0;
        }
        
        .logo-right {
            position: absolute;
            right: 120px;
            top: 0;
        }
        
        .logo-left img, .logo-right img {
            width: 48px;
            height: auto;
        }
        
        .title-section {
            text-align: center;
            max-width: 720px;
            margin: 0 auto;
        }
        
        .gov-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .sub-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        
        .doc-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .meta-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 6px;
            border-left: 4px solid #1e40af;
        }
        
        .meta-item {
            font-size: 11px;
        }
        
        .meta-label {
            font-weight: 600;
            color: #64748b;
        }
        
        .meta-value {
            color: #1e293b;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            text-align: center;
        }

        .summary-card:nth-child(1) {
            border-top: 4px solid #3b82f6;
        }

        .summary-card:nth-child(2) {
            border-top: 4px solid #f59e0b;
        }

        .summary-card:nth-child(3) {
            border-top: 4px solid #10b981;
        }

        .summary-card:nth-child(4) {
            border-top: 4px solid #8b5cf6;
        }

        .card-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-value {
            font-size: 28px;
            font-weight: 700;
        }

        .summary-card:nth-child(1) .card-value {
            color: #1e293b;
        }

        .summary-card:nth-child(2) .card-value {
            color: #dc2626;
        }

        .summary-card:nth-child(3) .card-value {
            color: #059669;
        }

        .summary-card:nth-child(4) .card-value {
            color: #1d4ed8;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .data-table th {
            background: #f8fafc;
            color: #374151;
            padding: 8px 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table th:last-child {
            border-right: none;
        }
        
        .data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        
        .data-table td:last-child {
            border-right: none;
        }
        
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #fafbfc;
        }
        
        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        
        .currency {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
        }
        
        .percentage-badge {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-radius: 12px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #4f46e5;
            display: inline-block;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .data-table {
                page-break-inside: avoid;
            }
            
            .summary-cards {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-left">
            <img src="{{ public_path('images/PGLU_logo.jpg') }}" alt="left-logo" style="width:48px; height:auto;">
        </div>
        <div class="logo-right">
            <img src="{{ public_path('images/Bagong-Pilipinas.png') }}" alt="right-logo" style="width:48px; height:auto;">
        </div>
        <div class="title-section">
            <div class="gov-title">Province of La Union</div>
            <div class="sub-title">Office of the Sangguniang Panlalawigan</div>
            <div class="doc-title">Boardmember Comparison Report</div>
        </div>
    </div>
    
    <div class="meta-section">
        <div class="meta-item">
            <span class="meta-label">Generated:</span>
            <span class="meta-value">{{ now()->format('F d, Y h:i A') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Period:</span>
            <span class="meta-value">{{ $periodLabel }}</span>
        </div>
    </div>

    

    <div class="section-title">Boardmember Comparison ({{ $periodLabel }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">Rank</th>
                <th>Boardmember</th>
                <th>Office</th>
                <th class="text-right">Fuel Cost</th>
                <th class="text-right">Maintenance</th>
                <th class="text-right">Total</th>
                <th class="text-center">% of Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sortedStats = collect($boardmemberStats)->sortByDesc(function($item) {
                    return ($item['fuelSlipCost'] ?? 0) + ($item['maintenanceCost'] ?? 0);
                });
                $grandTotal = $sortedStats->sum('fuelSlipCost') + $sortedStats->sum('maintenanceCost');
                $rank = 1;
            @endphp
            @forelse($sortedStats as $id => $stats)
                @php
                    $total = ($stats['fuelSlipCost'] ?? 0) + ($stats['maintenanceCost'] ?? 0);
                    $percentage = $grandTotal > 0 ? ($total / $grandTotal) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">#{{ $rank++ }}</td>
                    <td>{{ $stats['name'] }}</td>
                    <td>{{ $stats['office'] ?? 'N/A' }}</td>
                    <td class="text-right currency">₱{{ number_format($stats['fuelSlipCost'] ?? 0, 2) }}</td>
                    <td class="text-right currency">₱{{ number_format($stats['maintenanceCost'] ?? 0, 2) }}</td>
                    <td class="text-right currency"><strong>₱{{ number_format($total, 2) }}</strong></td>
                    <td class="text-center">
                        <span class="percentage-badge">{{ number_format($percentage, 1) }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:20px; font-style: italic; color: #64748b;">
                        No data available for the selected period
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div><strong>BM Vehicle Monitoring System</strong></div>
        <div>Boardmember Comparison Report - Generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</div>
        <div style="margin-top: 5px; font-size: 9px; color: #94a3b8;">
            Province of La Union • Office of the Sangguniang Panlalawigan
        </div>
    </div>
</body>
</html>
