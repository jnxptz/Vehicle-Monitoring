@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/vehicles-styles.css') }}">
<style>
    /* Fixed Header and Sidebar Layout */
    .dashboard-header {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1100 !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px);
        height: 70px;
        padding: 10px 20px !important;
    }

    .dashboard-body {
        margin-top: 70px; /* Offset for fixed header */
        display: flex;
        height: calc(100vh - 70px);
        overflow: hidden;
        padding: 0 !important;
        gap: 0 !important;
    }

    .dashboard-nav {
        position: fixed !important;
        top: 70px;
        left: 0;
        width: 240px;
        height: calc(100vh - 70px) !important;
        overflow-y: auto;
        z-index: 1000;
        border-radius: 0 !important;
        margin: 0 !important;
        border-right: 1px solid #e2e8f0;
        flex: none !important;
    }

    .dashboard-container {
        margin-left: 240px; /* Offset for fixed sidebar */
        display: flex !important;
        flex-direction: column !important;
        flex: 1;
        overflow-y: auto !important;
        height: calc(100vh - 70px);
        padding: 24px !important;
        border: none !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        scrollbar-width: thin;
    }

    /* Mobile overrides */
    @media (max-width: 768px) {
        .dashboard-nav {
            display: none !important;
        }
        .dashboard-container {
            margin-left: 0 !important;
            padding: 16px !important;
        }
    }
</style>

<div class="dashboard-page">
    <div class="dashboard-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: nowrap; padding: 16px 20px;">
        <div class="dashboard-title" style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo" style="height: 48px; width: auto; object-fit: contain; flex-shrink: 0;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Vehicle Monitoring System</h1>
        </div>

        
        {{-- Sidebar
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
                <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a>
                <div class="logout-form">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </nav>
        </div> --}}
    </div>

    <div class="dashboard-body">

        <nav class="dashboard-nav">
            @include('partials.sidebar-profile')
            
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Reports</a>

            <div class="bottom-section">
                <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
            </div>
            
            <div class="logout-form">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <div>
                    <h2>Vehicles</h2>
                    <p class="sub-text">Manage vehicle fleet</p>
                </div>
                
                <form method="GET" action="{{ route('vehicles.index') }}" class="filter-bar">
                    <select name="office" onchange="this.form.submit()">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                            <option value="{{ $office->id }}" {{ request('office') == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button" onclick="showAllVehicles()" class="add-vehicle-btn" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; color: white !important; margin-right: 8px;">📋 Show All Vehicles</button>

                    <button type="button" onclick="openVehicleModal()" class="add-vehicle-btn" style="background: linear-gradient(135deg, #ff9b00 0%, #d97706 100%) !important; color: white !important;">+ Register Vehicle</button>
                </form>
            </div>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($boardmembers) && $boardmembers->count() > 0)
                <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden; max-height: 500px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .table-wrapper::-webkit-scrollbar {
                            display: none;
                        }
                    </style>
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">#</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Board Member</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Vehicles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($boardmembers as $bm)

                                <tr class="main-row" onclick="toggleRow('bm-{{ $bm->id }}')" style="cursor:pointer; background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                    <td style="padding: 16px 20px; font-weight: 500; color: #1e40af; border: none;">{{ $counter }}</td>
                                    <td style="padding: 16px 20px; font-weight: 500; color: #1e293b; border: none;">{{ $bm->name }}</td>
                                    <td style="padding: 16px 20px; color: #64748b; border: none;">
                                        <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                            {{ $bm->vehicles->count() }} vehicle(s)
                                        </span>
                                    </td>
                                </tr>

                                <tr id="bm-{{ $bm->id }}-details" class="details-row" style="display:none; background: #ffffff;">
                                    <td colspan="3" style="padding: 0; border: none;">
                                        <div class="vehicle-cards">
                                            @forelse($bm->vehicles as $vehicle)
                                                <div class="vehicle-card">
                                                    <div class="vehicle-header">
                                                        <strong>{{ $vehicle->vehicle_name ?? $vehicle->plate_number }}</strong>
                                                        <div class="card-actions">
                                                            <button onclick="openEditModal({{ $vehicle->id }}, '{{ addslashes($vehicle->plate_number) }}', {{ $vehicle->monthly_fuel_limit }}, {{ $vehicle->current_km ?? 0 }}, '{{ addslashes($vehicle->driver ?? '') }}')" title="Edit" style="background:#0b77d6; color:white; border:none; padding:6px; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center;"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this vehicle?')" title="Delete" style="background:#dc3545; color:white; border:none; padding:6px; border-radius:4px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; margin-left:4px;"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="vehicle-body">
                                                        <div>Plate: {{ $vehicle->plate_number }}</div>
                                                        <div>Driver: {{ $vehicle->driver ?? 'Not Assigned' }}</div>
                                                        <div>Monthly Limit: {{ $vehicle->monthly_fuel_limit }} liters</div>
                                                        <div>Current KM: {{ $vehicle->latestFuelSlip?->km_reading ?? $vehicle->current_km ?? 0 }} km</div>
                                                        <div class="kpi-row">
                                                            <div class="kpi">
                                                                <small>Fuel</small>
                                                                <span class="amount">{{ '₱' . number_format(($vehicle->fuelSlips->sum('total_cost') ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="kpi">
                                                                <small>Maintenance</small>
                                                                <span class="amount">{{ '₱' . number_format(($vehicle->maintenances->sum('cost') ?? 0), 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-message">No vehicles registered for this boardmember.</div>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>

                                @php $counter++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty-message">No boardmembers or vehicles found.</p>
            @endif
        </div> {{-- dashboard-container --}}
    </div> {{-- dashboard-body --}}
</div> {{-- dashboard-page --}}

<script src="{{ asset('js/vehicles.js') }}"></script>

<!-- Edit Vehicle Modal -->
<div id="editVehicleModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Edit Vehicle</h2>
            <span onclick="closeEditModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form id="editVehicleForm" method="POST" style="display:none;">
            @csrf
            @method('PUT')
            <input type="hidden" id="editVehicleId" name="vehicle_id" value="">

            <label for="edit_plate_number" style="display:block; margin-bottom:12px; font-weight:600;">Plate Number:</label>
            <input id="edit_plate_number" type="text" name="plate_number" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" placeholder="e.g., ABC 1234">

            <label for="edit_driver" style="display:block; margin-bottom:12px; font-weight:600;">Driver Name:</label>
            <input id="edit_driver" type="text" name="driver" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" placeholder="Enter driver name">

            <label for="edit_monthly_fuel_limit" style="display:block; margin-bottom:12px; font-weight:600;">Monthly Fuel Limit (liters):</label>
            <input id="edit_monthly_fuel_limit" type="number" name="monthly_fuel_limit" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" min="1" step="0.01">

            <label for="edit_current_km" style="display:block; margin-bottom:12px; font-weight:600;">Current KM:</label>
            <input id="edit_current_km" type="number" name="current_km" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" min="0">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Update Vehicle</button>
        </form>
    </div>
</div>

<!-- Vehicle Modal -->
<div id="vehicleModal" style="display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4); backdrop-filter:blur(4px);">
    <div style="background:linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); margin:5% auto; padding:32px; border:1px solid #e2e8f0; border-radius:12px; width:90%; max-width:520px; max-height:85vh; overflow-y:auto; box-shadow:0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);" onclick="event.stopPropagation();">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid #e2e8f0;">
            <h2 style="margin:0; font-size:20px; font-weight:700; color:#1e293b;">Register Vehicle</h2>
            <span onclick="closeVehicleModal()" style="color:#64748b; font-size:24px; font-weight:400; cursor:pointer; padding:4px; border-radius:6px; transition:all 0.2s ease;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#475569';" onmouseout="this.style.background='transparent'; this.style.color='#64748b';">&times;</span>
        </div>

        <form action="{{ route('vehicles.store') }}" method="POST" onclick="event.stopPropagation();">
            @csrf

            @if(isset($allBoardmembers))
                <label for="boardmember_id" style="display:block; margin-bottom:8px; font-weight:600; color:#374151; font-size:14px;">Boardmember:</label>
                <select id="boardmember_id" name="boardmember_id" required style="width:100%; padding:12px 16px; margin-bottom:20px; border:1px solid #d1d5db; border-radius:8px; background:#ffffff; color:#374151; font-size:14px; transition:all 0.2s ease; box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.05);" onmouseover="this.style.borderColor='#9ca3af'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';" onmouseout="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';">
                    <option value="">-- Select Boardmember --</option>
                    @foreach($allBoardmembers as $boardmember)
                        <option value="{{ $boardmember->id }}">{{ $boardmember->name }} ({{ $boardmember->office->name ?? 'No Office' }})</option>
                    @endforeach
                </select>
            @endif

            <label for="vehicle_name" style="display:block; margin-bottom:8px; font-weight:600; color:#374151; font-size:14px;">Vehicle Name:</label>
            <input id="vehicle_name" type="text" name="vehicle_name" required style="width:100%; padding:12px 16px; margin-bottom:20px; border:1px solid #d1d5db; border-radius:8px; background:#ffffff; color:#374151; font-size:14px; transition:all 0.2s ease; box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.05);" placeholder="e.g., Toyota Corolla" onmouseover="this.style.borderColor='#9ca3af'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';" onmouseout="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';">

            <label for="plate_number" style="display:block; margin-bottom:8px; font-weight:600; color:#374151; font-size:14px;">Plate Number:</label>
            <input id="plate_number" type="text" name="plate_number" required style="width:100%; padding:12px 16px; margin-bottom:20px; border:1px solid #d1d5db; border-radius:8px; background:#ffffff; color:#374151; font-size:14px; transition:all 0.2s ease; box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.05);" placeholder="e.g., ABC 1234" onmouseover="this.style.borderColor='#9ca3af'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';" onmouseout="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';">

            <label for="driver" style="display:block; margin-bottom:8px; font-weight:600; color:#374151; font-size:14px;">Driver Name:</label>
            <input id="driver" type="text" name="driver" required style="width:100%; padding:12px 16px; margin-bottom:20px; border:1px solid #d1d5db; border-radius:8px; background:#ffffff; color:#374151; font-size:14px; transition:all 0.2s ease; box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.05);" placeholder="Enter driver name" onmouseover="this.style.borderColor='#9ca3af'; this.style.boxShadow='0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)';" onmouseout="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)';">


            <button type="submit" style="background:linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color:white; padding:14px 24px; border:none; border-radius:8px; cursor:pointer; width:100%; font-weight:600; font-size:14px; transition:all 0.2s ease; box-shadow:0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1e40af 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 10px 15px -3px rgba(59, 130, 246, 0.4), 0 4px 6px -2px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.2)';">Register Vehicle</button>
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

<!-- Show All Vehicles Modal -->
<div id="showAllVehiclesModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background-color: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 800px; max-height: 80vh; box-shadow: 0 10px 25px rgba(0,0,0,0.2); overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 600;">📋 All Vehicles</h3>
            <button type="button" onclick="closeShowAllVehiclesModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; border: none;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                        <th style="padding: 12px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Vehicle</th>
                        <th style="padding: 12px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Boardmember</th>
                        <th style="padding: 12px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Office</th>
                        <th style="padding: 12px 16px; text-align: center; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Status</th>
                    </tr>
                </thead>
                <tbody id="allVehiclesList">
                    <!-- Vehicles will be loaded here via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Vehicle Assignment Modal -->
<div id="assignVehicleModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
    <div style="background-color: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 600;">🚗 Assign Vehicle</h3>
            <button type="button" onclick="closeAssignVehicleModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        
        <form id="assignVehicleForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <input type="hidden" id="assignVehicleId" name="vehicle_id">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Vehicle:</label>
                <div id="assignVehicleInfo" style="padding: 12px; background: #f8fafc; border-radius: 8px; color: #374151;"></div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="assignBoardmemberId" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Assign to Boardmember:</label>
                <select id="assignBoardmemberId" name="boardmember_id" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                    <option value="">-- Select Boardmember --</option>
                    @if(isset($allBoardmembers))
                        @foreach($allBoardmembers as $boardmember)
                            <option value="{{ $boardmember->id }}">{{ $boardmember->name }} ({{ $boardmember->office->name ?? 'No Office' }})</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeAssignVehicleModal()" style="padding: 12px 20px; background: #f1f5f9; color: #475569; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Cancel</button>
                <button type="submit" style="padding: 12px 20px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">Assign Vehicle</button>
            </div>
        </form>
    </div>
</div>


<script>
function showAllVehicles() {
    // Show the modal
    document.getElementById('showAllVehiclesModal').style.display = 'flex';
    
    // Load all vehicles via AJAX
    loadAllVehicles();
}

function closeShowAllVehiclesModal() {
    document.getElementById('showAllVehiclesModal').style.display = 'none';
}

function loadAllVehicles() {
    fetch('/api/all-vehicles')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('allVehiclesList');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="padding: 20px; text-align: center; color: #64748b;">No vehicles found</td></tr>';
                return;
            }
            
            data.forEach(vehicle => {
                const row = document.createElement('tr');
                row.style.cssText = 'background: ' + (data.indexOf(vehicle) % 2 === 0 ? '#f8fafc' : '#ffffff') + '; border-bottom: 1px solid #e2e8f0;';
                
                const statusBadge = vehicle.bm_id 
                    ? '<span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #166534;">✓ Assigned</span>'
                    : '<span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #dc2626;">✗ Unassigned</span>';
                
                const actionsButton = !vehicle.bm_id 
                    ? `<button type="button" onclick="openAssignModal(${vehicle.id}, '${vehicle.make} ${vehicle.model}', '${vehicle.plate_number || 'N/A'}')" style="padding: 6px 12px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">Assign</button>`
                    : '<span style="color: #94a3b8; font-size: 12px;">—</span>';
                
                row.innerHTML = `
                    <td style="padding: 12px 16px; border: none; color: #374151;">
                        <strong>${vehicle.make} ${vehicle.model}</strong><br>
                        <small style="color: #64748b;">${vehicle.plate_number || 'N/A'}</small>
                    </td>
                    <td style="padding: 12px 16px; border: none; color: #374151;">
                        ${vehicle.boardmember ? vehicle.boardmember.name : '<span style="color: #94a3b8; font-style: italic;">Unassigned</span>'}
                    </td>
                    <td style="padding: 12px 16px; border: none; color: #374151;">
                        ${vehicle.boardmember && vehicle.boardmember.office ? vehicle.boardmember.office.name : '<span style="color: #94a3b8; font-style: italic;">N/A</span>'}
                    </td>
                    <td style="padding: 12px 16px; border: none; text-align: center;">
                        ${statusBadge}
                    </td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error loading vehicles:', error);
            document.getElementById('allVehiclesList').innerHTML = 
                '<tr><td colspan="5" style="padding: 20px; text-align: center; color: #ef4444;">Error loading vehicles</td></tr>';
        });
}

function openAssignModal(vehicleId, vehicleName, plateNumber) {
    document.getElementById('assignVehicleId').value = vehicleId;
    document.getElementById('assignVehicleInfo').innerHTML = `<strong>${vehicleName}</strong><br><small style="color: #64748b;">Plate: ${plateNumber}</small>`;
    
    // Set the form action dynamically with the vehicle ID
    const form = document.getElementById('assignVehicleForm');
    form.action = `/vehicles/${vehicleId}/assign`;
    
    document.getElementById('assignVehicleModal').style.display = 'flex';
}

function closeAssignVehicleModal() {
    document.getElementById('assignVehicleModal').style.display = 'none';
    document.getElementById('assignVehicleForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const showAllModal = document.getElementById('showAllVehiclesModal');
    const assignModal = document.getElementById('assignVehicleModal');
    
    if (event.target == showAllModal) {
        closeShowAllVehiclesModal();
    }
    
    if (event.target == assignModal) {
        closeAssignVehicleModal();
    }
}
</script>

@endsection
