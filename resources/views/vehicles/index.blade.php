@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Admin Dashboard</h1>
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
                <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
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
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Boardmembers</a>

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
                <h2>Vehicles</h2>
                <button onclick="openVehicleModal()" class="btn-primary btn-sm">+ Register Vehicle</button>
            </div>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($boardmembers) && $boardmembers->count() > 0)
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Board Member</th>
                                <th>Vehicles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($boardmembers as $bm)

                                <tr class="main-row" onclick="toggleRow('bm-{{ $bm->id }}')" style="cursor:pointer;">
                                    <td>{{ $counter }}</td>
                                    <td>{{ $bm->name }}</td>
                                    <td>{{ $bm->vehicles->count() }} vehicle(s)</td>
                                </tr>

                                <tr id="bm-{{ $bm->id }}-details" class="details-row" style="display:none;">
                                    <td colspan="3">
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
                                                                <small>Fuel YTD</small>
                                                                <span class="amount">{{ '₱' . number_format(($vehicle->fuelSlips->sum('cost') ?? 0), 2) }}</span>
                                                            </div>
                                                            <div class="kpi">
                                                                <small>Maintenance YTD</small>
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

    // Modal functions
    function openVehicleModal() {
        document.getElementById('vehicleModal').style.display = 'block';
    }

    function closeVehicleModal() {
        document.getElementById('vehicleModal').style.display = 'none';
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

    // Toggle recent fuel slips list inside a vehicle card
    function toggleFuelList(vehicleId) {
        const el = document.getElementById('fuel-' + vehicleId);
        if (!el) return;
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }

    // Edit modal functions
    function openEditModal(vehicleId, plateNumber, monthlyLimit, currentKm) {
        document.getElementById('editVehicleId').value = vehicleId;
        document.getElementById('edit_plate_number').value = plateNumber;
        document.getElementById('edit_monthly_fuel_limit').value = monthlyLimit;
        document.getElementById('edit_current_km').value = currentKm;
        // Set the form action dynamically
        document.getElementById('editVehicleForm').action = '/vehicles/' + vehicleId;
        document.getElementById('editVehicleForm').style.display = 'block';
        document.getElementById('editVehicleModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editVehicleModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('editVehicleModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }
</script>

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
<div id="vehicleModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Register Vehicle</h2>
            <span onclick="closeVehicleModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf

            @if(isset($boardmembers))
                <label for="boardmember_id" style="display:block; margin-bottom:12px; font-weight:600;">Boardmember:</label>
                <select id="boardmember_id" name="boardmember_id" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px;">
                    <option value="">-- Select Boardmember --</option>
                    @foreach($boardmembers as $boardmember)
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
@endsection
