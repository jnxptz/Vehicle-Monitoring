@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/vehicles-styles.css') }}">

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>

        @include('partials.user-profile-dropdown')

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
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>

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

                    <button type="button" onclick="openVehicleModal()" class="add-vehicle-btn">+ Register Vehicle</button>
                </form>
            </div>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($boardmembers) && $boardmembers->count() > 0)
                <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
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
                                                            <button onclick="openEditModal({{ $vehicle->id }}, '{{ addslashes($vehicle->plate_number) }}', {{ $vehicle->monthly_fuel_limit }}, {{ $vehicle->current_km ?? 0 }})" style="background:#0b77d6; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600;">Edit</button>
                                                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this vehicle?')" style="background:#dc3545; color:white; border:none; padding:6px 12px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; margin-left:8px;">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="vehicle-body">
                                                        <div>Plate: {{ $vehicle->plate_number }}</div>
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

            <label for="edit_monthly_fuel_limit" style="display:block; margin-bottom:12px; font-weight:600;">Monthly Fuel Limit (liters):</label>
            <input id="edit_monthly_fuel_limit" type="number" name="monthly_fuel_limit" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" min="1" step="0.01">

            <label for="edit_current_km" style="display:block; margin-bottom:12px; font-weight:600;">Current KM:</label>
            <input id="edit_current_km" type="number" name="current_km" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" min="0">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Update Vehicle</button>
        </form>
    </div>
</div>

<!-- Vehicle Modal -->
<div id="vehicleModal" style="display:none; position:fixed; z-index:999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px; max-height:80vh; overflow-y:auto;" onclick="event.stopPropagation();">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Register Vehicle</h2>
            <span onclick="closeVehicleModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('vehicles.store') }}" method="POST" onclick="event.stopPropagation();">
            @csrf

            @if(isset($allBoardmembers))
                <label for="boardmember_id" style="display:block; margin-bottom:12px; font-weight:600;">Boardmember:</label>
                <select id="boardmember_id" name="boardmember_id" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;">
                    <option value="">-- Select Boardmember --</option>
                    @foreach($allBoardmembers as $boardmember)
                        <option value="{{ $boardmember->id }}">{{ $boardmember->name }} ({{ $boardmember->office->name ?? 'No Office' }})</option>
                    @endforeach
                </select>
            @endif

            <label for="vehicle_name" style="display:block; margin-bottom:12px; font-weight:600;">Vehicle Name:</label>
            <input id="vehicle_name" type="text" name="vehicle_name" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" placeholder="e.g., Toyota Corolla">

            <label for="plate_number" style="display:block; margin-bottom:12px; font-weight:600;">Plate Number:</label>
            <input id="plate_number" type="text" name="plate_number" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" placeholder="e.g., ABC 1234">

            <label for="driver" style="display:block; margin-bottom:12px; font-weight:600;">Driver Name:</label>
            <input id="driver" type="text" name="driver" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;" placeholder="Enter driver name">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Register Vehicle</button>
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

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>
@endsection
