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

        {{-- Sidebar --}}
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
        </div>
    </div>

    <div class="dashboard-body">

        <nav class="dashboard-nav">
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

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <h2>Vehicles</h2>
                <a href="{{ route('vehicles.create') }}" class="btn-primary btn-sm">+ Register Vehicle</a>
            </div>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if($vehicles->count() > 0)
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Board Member</th>
                                <th>Plate Number</th>
                                <th>Monthly Fuel Limit</th>
                                <th>Current KM</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $vehicle->bm->name ?? 'N/A' }}</td>
                                    <td>{{ $vehicle->plate_number }}</td>
                                    <td>{{ $vehicle->monthly_fuel_limit }} liters</td>
                                    <td>{{ $vehicle->latestFuelSlip?->km_reading ?? $vehicle->current_km ?? 0 }} km</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn-edit">Edit</a>
                                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this vehicle?')" class="btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="empty-message">No vehicles found.</p>
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
</script>
@endsection
