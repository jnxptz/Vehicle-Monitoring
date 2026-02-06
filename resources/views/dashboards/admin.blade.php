@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>
        
        <div class="hamburger-menu-wrapper">
            <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
            <label for="hamburger-toggle" class="hamburger-btn">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <nav class="hamburger-dropdown">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </nav>
        </div>
    </div>

    <div class="dashboard-body">

        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </nav>

        <div class="dashboard-container">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
                <h2 style="margin:0;">Boardmembers Overview</h2>
                <form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:10px;">
                    <label for="month" style="font-weight: 600; color:#475569;">Month:</label>
                    <select id="month" name="month" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px;">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <span style="font-size: 14px; color: #607d8b;">{{ $selectedMonthName }} {{ $year }}</span>
                    </form>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <a href="{{ route('admin.dashboard.yearly.pdf') }}" class="btn" style="background:#FF9B00; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:600;">Export Yearly PDF</a>
                    </div>
            </div>

            @if(empty($rows) || $rows->count() === 0)
                <p class="empty-message">No boardmembers found.</p>
            @else
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Boardmember</th>
                                <th>Plate #</th>
                                <th>Yearly Budget</th>
                                <th>Total Used (YTD)</th>
                                <th>Remaining Budget</th>
                                <th>Liters Used ({{ $selectedMonthName }})</th>
                                <th>Budget Recommendation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                @php
                                    $warnBudget = $row['yearlyBudget'] > 0 && $row['budgetUsedPercentage'] >= 80;
                                    $overBudget = $row['yearlyBudget'] > 0 && $row['remainingBudget'] < 0;
                                    $overMonthly = $row['monthlyLimit'] > 0 && $row['monthlyLitersUsed'] > $row['monthlyLimit'];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div style="font-weight: 600;">{{ $row['user']->name }}</div>
                                        <div style="font-size: 12px; color:#607d8b;">{{ $row['user']->email }}</div>
                                    </td>
                                    <td>{{ $row['vehicle']?->plate_number ?? '—' }}</td>
                                    <td>₱{{ number_format((float) $row['yearlyBudget'], 2) }}</td>
                                    <td>₱{{ number_format((float) $row['totalUsed'], 2) }}</td>
                                    <td style="{{ $overBudget ? 'color:#d32f2f; font-weight:700;' : ($warnBudget ? 'color:#FF9B00; font-weight:700;' : '') }}">
                                        ₱{{ number_format((float) $row['remainingBudget'], 2) }}
                                    </td>
                                    <td style="{{ $overMonthly ? 'color:#d32f2f; font-weight:700;' : '' }}">
                                        {{ number_format((float) $row['monthlyLitersUsed'], 2) }} L
                                    </td>
                                    <td>
                                        @if(isset($row['budgetRecommendation']) && $row['budgetRecommendation'])
                                            <div style="font-size: 12px;">
                                                <span style="
                                                    display: inline-block;
                                                    padding: 4px 8px;
                                                    border-radius: 4px;
                                                    font-weight: 600;
                                                    white-space: nowrap;
                                                    @if($row['budgetRecommendation']['status'] === 'increase')
                                                        background: #fef2f2;
                                                        color: #dc2626;
                                                    @elseif($row['budgetRecommendation']['status'] === 'decrease')
                                                        background: #f0fdf4;
                                                        color: #16a34a;
                                                    @else
                                                        background: #f0f9ff;
                                                        color: #0369a1;
                                                    @endif
                                                ">
                                                    {{ ucfirst($row['budgetRecommendation']['status']) }}
                                                </span>
                                                <div style="font-size: 11px; color: #475569; margin-top: 4px;">
                                                    Suggest: ₱{{ number_format((float) $row['budgetRecommendation']['suggestedBudget'], 2) }}
                                                </div>
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div> 
    </div> 
</div>

<script>
    // Close hamburger menu when a link is clicked
    document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('hamburger-toggle').checked = false;
        });
    });

    // Also handle form submission (logout)
    document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
        form.addEventListener('submit', () => {
            document.getElementById('hamburger-toggle').checked = false;
        });
    });
</script>
@endsection
