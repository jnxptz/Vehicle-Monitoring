@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@if(auth()->user()->role === 'admin')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif

<style>
    /* Ensure hamburger menu is positioned correctly in header */
    .dashboard-header .hamburger-menu-wrapper {
        margin-left: auto;
    }

    /* Hide hamburger menu on desktop (larger screens) */
    @media (min-width: 769px) {
        .hamburger-menu-wrapper {
            display: none !important;
        }

        .dashboard-nav {
            display: flex !important;
        }
    }
</style>

@if(auth()->user()->role === 'admin')
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
                <h1>Admin Dashboard</h1>
            </div>

            {{-- Hamburger Menu (Mobile/Tablet Only) --}}
            <div class="hamburger-menu-wrapper">
                <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
                <label for="hamburger-toggle" class="hamburger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <nav class="hamburger-dropdown">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
                    <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                    <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                    <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                    <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>
                    <div style="border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 8px;">
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="dashboard-body">

        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>
            <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <div>
                    <h2>Fuel Slips</h2>
                    <p class="sub-text">Manage fuel consumption records</p>
                </div>
                
                @if(auth()->user()->role === 'admin')
                <form method="GET" action="{{ route('fuel-slips.index') }}" class="filter-bar" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <select name="office" onchange="this.form.submit()" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; color: #1e293b; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ request('office') == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button" onclick="openFuelSlipModal()" class="btn-primary btn-sm" style="padding: 10px 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.2)';">+ Add Fuel Slip</button>
                </form>
                @else
                <button onclick="openFuelSlipModal()" class="btn-primary btn-sm" style="padding: 10px 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(245, 158, 11, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #f59e0b 0%, #d97706 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(245, 158, 11, 0.2)';">+ Add Fuel Slip</button>
                @endif
            </div>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="error-message">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Fuel Slips Table --}}
                @if($boardmembers && $boardmembers->count() > 0)
                    <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; border: none;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                                    <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">#</th>
                                    <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Board Member</th>
                                    <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Fuel Slips</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($boardmembers as $bm)
                                    <tr class="main-row" onclick="toggleRow('fs-{{ $bm->id }}')" style="cursor:pointer; background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                        <td style="padding: 16px 20px; font-weight: 500; color: #1e40af; border: none;">{{ $counter }}</td>
                                        <td style="padding: 16px 20px; font-weight: 500; color: #1e293b; border: none;">{{ $bm->name }}</td>
                                        <td style="padding: 16px 20px; color: #64748b; border: none;">
                                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                                {{ $bm->fuelSlips->count() }} slip(s)
                                            </span>
                                        </td>
                                    </tr>

                                    <tr id="fs-{{ $bm->id }}-details" class="details-row" style="display:none; background: #ffffff;">
                                        <td colspan="3" style="padding: 0; border: none;">
                                            <div style="overflow-x:auto;">
                                                @if($bm->fuelSlips->count() > 0)
                                                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                                                        @foreach($bm->fuelSlips as $slip)
                                                            <div style="border:1px solid #e6eef8; border-radius:8px; padding:16px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                                                    <strong style="font-size:15px;">{{ $slip->vehicle_name }}</strong>
                                                                    <a href="{{ route('fuel-slips.exportPDF', $slip->id) }}" style="background:#ff9b00; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; text-decoration:none;">PDF</a>
                                                                </div>
                                                                <div style="font-size:13px; line-height:1.8;">
                                                                    <div><span style="color:#6b7280;">Plate #:</span> <strong>{{ $slip->plate_number }}</strong></div>
                                                                    <div><span style="color:#6b7280;">Liters:</span> {{ $slip->liters }}</div>
                                                                    <div><span style="color:#6b7280;">Cost:</span> <strong>₱{{ number_format($slip->total_cost, 2) }}</strong></div>
                                                                    <div><span style="color:#6b7280;">KM:</span> {{ $slip->km_reading }}</div>
                                                                    <div><span style="color:#6b7280;">Driver:</span> {{ $slip->driver }}</div>
                                                                    <div><span style="color:#6b7280;">Date:</span> {{ \Carbon\Carbon::parse($slip->date)->format('m/d/Y') }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div style="padding:12px; color:#6b7280;">No fuel slips for this boardmember.</div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="empty-message">No fuel slips found.</p>
                @endif
            </div> {{-- dashboard-container --}}
        </div> {{-- dashboard-body --}}
    </div> {{-- dashboard-page --}}
@else
    {{-- Boardmember View --}}
    <div class="dashboard-page">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
                <h1>Sangguniang Panlalawigan</h1>
            </div>

            {{-- Hamburger Menu (Mobile Only) --}}
            <div class="hamburger-menu-wrapper">
                <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
                <label for="hamburger-toggle" class="hamburger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <nav class="hamburger-dropdown">
                    <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                    <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                    <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="dashboard-body">

            <nav class="dashboard-nav">
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </nav>

            <div class="dashboard-container">
                <div class="page-header">
                    <h2>Fuel Slips</h2>
                </div>

                {{-- Success/Error Messages --}}
                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="error-message">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Fuel Slips Table (Boardmember View) --}}
                @if($fuelSlips && $fuelSlips->count() > 0)
                    <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; border: none;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Vehicle</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Plate #</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Liters</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Cost</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">KM</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Driver</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Control #</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Date</th>
                                    <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuelSlips as $slip)
                                    <tr style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                        <td style="padding: 14px 16px; font-weight: 500; color: #1e293b; border: none;">{{ $slip->vehicle_name }}</td>
                                        <td style="padding: 14px 16px; color: #64748b; border: none;">{{ $slip->plate_number }}</td>
                                        <td style="padding: 14px 16px; color: #1e293b; border: none;">{{ $slip->liters }}</td>
                                        <td style="padding: 14px 16px; font-weight: 600; color: #059669; border: none;">₱{{ number_format($slip->total_cost, 2) }}</td>
                                        <td style="padding: 14px 16px; color: #64748b; border: none;">{{ $slip->km_reading }}</td>
                                        <td style="padding: 14px 16px; color: #64748b; border: none;">{{ $slip->driver }}</td>
                                        <td style="padding: 14px 16px; color: #64748b; border: none;">{{ $slip->control_number }}</td>
                                        <td style="padding: 14px 16px; color: #64748b; border: none; font-size: 13px;">{{ $slip->date }}</td>
                                        <td style="padding: 14px 16px; border: none;">
                                            <a href="{{ route('fuel-slips.exportPDF', $slip->id) }}" style="background: #ff9b00; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block;">PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="empty-message">No fuel slips found.</p>
                @endif
            </div> {{-- dashboard-container --}}
        </div> {{-- dashboard-body --}}
    </div> {{-- dashboard-page --}}
@endif

<script>
    // Close hamburger menu when a link is clicked
    document.querySelectorAll('.hamburger-dropdown a').forEach(link => {
        link.addEventListener('click', () => {
            const hamburgerToggle = document.getElementById('hamburger-toggle');
            if (hamburgerToggle) {
                hamburgerToggle.checked = false;
            }
        });
    });

    // Also handle form submission (logout)
    document.querySelectorAll('.hamburger-dropdown form').forEach(form => {
        form.addEventListener('submit', () => {
            const hamburgerToggle = document.getElementById('hamburger-toggle');
            if (hamburgerToggle) {
                hamburgerToggle.checked = false;
            }
        });
    });

    // Fuel Slip Modal functions
    function openFuelSlipModal() {
        document.getElementById('fuelSlipModal').style.display = 'block';
    }

    function closeFuelSlipModal() {
        document.getElementById('fuelSlipModal').style.display = 'none';
    }

    // Toggle details row for a boardmember
    function toggleRow(id) {
        const details = document.getElementById(id + '-details');
        if (!details) return;
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'table-row';
        } else {
            details.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        const modal = document.getElementById('fuelSlipModal');
        if (event.target === modal) {
            closeFuelSlipModal();
        }
    }
</script>

<!-- Fuel Slip Modal -->
<div id="fuelSlipModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:5% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:600px; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Add Fuel Slip</h2>
            <span onclick="closeFuelSlipModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('fuel-slips.store') }}" method="POST">
            @csrf

            <label for="boardmember_id" style="display:block; margin-bottom:12px; font-weight:600;">Boardmember (select to see their vehicles):</label>
            <select id="boardmember_id" name="boardmember_id" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="">-- Select boardmember --</option>
                @if(isset($boardmembers))
                    @foreach($boardmembers as $bm)
                        <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office->name ?? 'No Office' }})</option>
                    @endforeach
                @endif
            </select>

            <label for="vehicle_id" style="display:block; margin-bottom:12px; font-weight:600;">Registered Vehicle (optional):</label>
            <select id="vehicle_id" name="vehicle_id" disabled style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="">-- Select registered vehicle --</option>
            </select>

            <p style="font-size:12px; color:#666; margin-bottom:20px;">Tip: Select a boardmember first to filter their vehicles, then choose a registered vehicle to auto-fill vehicle name and plate number, or leave blank to enter new details.</p>

            <label for="vehicle_name" style="display:block; margin-bottom:12px; font-weight:600;">Vehicle Name:</label>
            <input id="vehicle_name" type="text" name="vehicle_name" placeholder="Enter vehicle name" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="plate_number" style="display:block; margin-bottom:12px; font-weight:600;">Plate Number:</label>
            <input id="plate_number" type="text" name="plate_number" placeholder="Enter plate number" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="liters" style="display:block; margin-bottom:12px; font-weight:600;">Liters:</label>
            <input id="liters" type="number" step="0.01" name="liters" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="unit_cost" style="display:block; margin-bottom:12px; font-weight:600;">Unit Cost (₱ per liter):</label>
            <input id="unit_cost" type="number" step="0.01" name="unit_cost" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="total_cost" style="display:block; margin-bottom:12px; font-weight:600;">Total Cost:</label>
            <input id="total_cost" type="number" step="0.01" name="total_cost" readonly style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box; background-color:#f5f5f5;">

            <label for="km_reading" style="display:block; margin-bottom:12px; font-weight:600;">KM Reading:</label>
            <input id="km_reading" type="number" name="km_reading" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="driver" style="display:block; margin-bottom:12px; font-weight:600;">Driver:</label>
            <input id="driver" type="text" name="driver" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="date" style="display:block; margin-bottom:12px; font-weight:600;">Date:</label>
            <input id="date" type="date" name="date" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="prepared_by_name" style="display:block; margin-bottom:12px; font-weight:600;">Prepared by Name:</label>
            <input id="prepared_by_name" type="text" name="prepared_by_name" placeholder="Enter name of person who prepared" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="approved_by_name" style="display:block; margin-bottom:12px; font-weight:600;">Approved by Name:</label>
            <input id="approved_by_name" type="text" name="approved_by_name" placeholder="Enter name of person who approved" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Submit</button>
        </form>

        @if ($errors->any())
            <div style="margin-top:20px; background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:12px; border-radius:4px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    const boardmemberSelect = document.getElementById('boardmember_id');
    const vehicleSelect = document.getElementById('vehicle_id');
    const nameInput = document.getElementById('vehicle_name');
    const plateInput = document.getElementById('plate_number');
    const litersInput = document.getElementById('liters');
    const unitCostInput = document.getElementById('unit_cost');
    const totalCostInput = document.getElementById('total_cost');

    function filterVehiclesByBoardmember(boardmemberId){
        const opts = vehicleSelect.querySelectorAll('option[data-boardmember]');
        opts.forEach(o => {
            if(boardmemberId && o.getAttribute('data-boardmember') !== boardmemberId) {
                o.style.display = 'none';
            } else {
                o.style.display = '';
            }
        });

        vehicleSelect.disabled = !boardmemberId;
        vehicleSelect.value = '';
        nameInput.value = '';
        plateInput.value = '';
    }

    function calculateTotalCost() {
        const liters = parseFloat(litersInput.value) || 0;
        const unitCost = parseFloat(unitCostInput.value) || 0;
        const totalCost = (liters * unitCost).toFixed(2);
        totalCostInput.value = totalCost;
    }

    boardmemberSelect && boardmemberSelect.addEventListener('change', function(){
        filterVehiclesByBoardmember(this.value);
    });

    vehicleSelect && vehicleSelect.addEventListener('change', function(){
        nameInput.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
        plateInput.value = this.options[this.selectedIndex].getAttribute('data-plate') || '';
    });

    litersInput && litersInput.addEventListener('change', calculateTotalCost);
    litersInput && litersInput.addEventListener('input', calculateTotalCost);
    unitCostInput && unitCostInput.addEventListener('change', calculateTotalCost);
    unitCostInput && unitCostInput.addEventListener('input', calculateTotalCost);

    // Populate vehicle options on page load
    const boardmembersData = @json(isset($boardmembers) ? $boardmembers->mapWithKeys(function($bm) { return [$bm->id => $bm->vehicles ?? []]; }) : []);
    
    Object.keys(boardmembersData).forEach(bmId => {
        boardmembersData[bmId].forEach(v => {
            const option = document.createElement('option');
            option.value = v.id;
            option.setAttribute('data-name', v.vehicle_name);
            option.setAttribute('data-plate', v.plate_number);
            option.setAttribute('data-boardmember', bmId);
            option.textContent = v.plate_number + ' — ' + v.vehicle_name;
            vehicleSelect.appendChild(option);
        });
    });
</script>

@endsection
