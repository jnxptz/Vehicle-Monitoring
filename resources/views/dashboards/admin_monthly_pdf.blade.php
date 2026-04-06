<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Admin Monthly Dashboard PDF</title>
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
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: flex-start;
        }
        
        .summary-card {
            flex: 0 0 auto;
            width: 180px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        
        .card-label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .card-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
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
        
        .vehicle-section {
            margin-bottom: 25px;
        }
        
        .vehicle-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            padding: 8px;
            background: #f1f5f9;
            border-radius: 4px;
            border-left: 3px solid #1e40af;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        
        .status-high {
            color: #dc2626;
            font-weight: 600;
        }
        
        .status-medium {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .status-low {
            color: #059669;
            font-weight: 600;
        }
        
        .currency {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
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
            <div class="doc-title">Board Member Monthly Report</div>
        </div>
    </div>
    
    <div class="meta-section">
        <div class="meta-item">
            <span class="meta-label">Generated:</span>
            <span class="meta-value">{{ now()->format('F d, Y h:i A') }}</span>
        </div>
        <div class="meta-item">
            <span class="meta-label">Period:</span>
            <span class="meta-value">{{ $selectedMonthName }} {{ $year }}</span>
        </div>
        @if($officeName)
            <div class="meta-item">
                <span class="meta-label">Office:</span>
                <span class="meta-value">{{ $officeName }}</span>
            </div>
        @endif
    </div>

    <div class="section-title">Monthly Overview</div>
    <div class="summary-cards">
        <div class="summary-card">
            <div class="card-label">Total Liters Used</div>
            <div class="card-value">{{ number_format($totalMonthlyLiters, 2) }} L</div>
        </div>
        <div class="summary-card">
            <div class="card-label">Total Cost</div>
            <div class="card-value currency">&#8369;{{ number_format($totalMonthlyCost, 2) }}</div>
        </div>
    </div>

    <div class="section-title">Boardmember Performance ({{ $selectedMonthName }} {{ $year }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Boardmember</th>
                <th class="text-right">Monthly Liters</th>
                <th class="text-right">Monthly Cost</th>
                <th class="text-right">Yearly Budget</th>
                <th class="text-right">Yearly Used</th>
                <th class="text-right">Yearly Remaining</th>
                <th class="text-right">Usage %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row['user']->name }}</td>
                    <td class="text-right">{{ number_format($row['monthlyLitersUsed'], 2) }}</td>
                    <td class="text-right currency">&#8369;{{ number_format($row['monthlyCostUsed'], 2) }}</td>
                    <td class="text-right currency">&#8369;{{ number_format($row['yearlyBudget'], 2) }}</td>
                    <td class="text-right currency">&#8369;{{ number_format($row['totalUsed'], 2) }}</td>
                    <td class="text-right currency {{ $row['remainingBudget'] < 0 ? 'status-high' : ($row['budgetUsedPercentage'] >= 80 ? 'status-medium' : 'status-low') }}">
                        &#8369;{{ number_format($row['remainingBudget'], 2) }}
                    </td>
                    <td class="text-right">
                        <span class="{{ $row['budgetUsedPercentage'] >= 90 ? 'status-high' : ($row['budgetUsedPercentage'] >= 80 ? 'status-medium' : 'status-low') }}">
                            {{ $row['budgetUsedPercentage'] }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:20px; font-style: italic; color: #64748b;">
                        No boardmembers found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($rows->count() > 0)
        <div class="section-title">Vehicle Breakdown</div>
        @foreach($rows as $row)
            @if(count($row['vehicles']) > 0)
                <div class="vehicle-section">
                    <div class="vehicle-title">{{ $row['user']->name }} - Vehicle Details</div>
                    <table class="data-table" style="font-size: 10px;">
                        <thead>
                            <tr>
                                <th>Vehicle Name</th>
                                <th class="text-right">Plate Number</th>
                                <th class="text-right">Fuel Cost</th>
                                <th class="text-right">Maintenance Cost</th>
                                <th class="text-right">Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($row['vehicles'] as $vehicle)
                                <tr>
                                    <td>{{ $vehicle['vehicle']->vehicle_name }}</td>
                                    <td class="text-right">{{ $vehicle['vehicle']->plate_number }}</td>
                                    <td class="text-right currency">&#8369;{{ number_format($vehicle['fuelSlipCost'], 2) }}</td>
                                    <td class="text-right currency">&#8369;{{ number_format($vehicle['maintenanceCost'], 2) }}</td>
                                    <td class="text-right currency">
                                        <strong>&#8369;{{ number_format($vehicle['fuelSlipCost'] + $vehicle['maintenanceCost'], 2) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    @endif

    <div class="footer">
        <div><strong>BM Vehicle Monitoring System</strong></div>
        <div>Monthly Fleet Report - Generated on {{ now()->format('F d, Y') }} at {{ now()->format('h:i A') }}</div>
        <div style="margin-top: 5px; font-size: 9px; color: #94a3b8;">
            Province of La Union • Office of the Sangguniang Panlalawigan
        </div>
    </div>
</body>
</html>
