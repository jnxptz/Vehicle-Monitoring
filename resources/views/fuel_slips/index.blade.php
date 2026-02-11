@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@if(auth()->user()->role === 'admin')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif

@if(auth()->user()->role === 'admin')
    <div class="dashboard-page">
        {{-- Header --}}
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
                <h2>Fuel Slips</h2>
                <a href="{{ route('fuel-slips.create') }}" class="btn-primary btn-sm">+ Add Fuel Slip</a>
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
                @if($fuelSlips->count() > 0)
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Plate #</th>
                                    <th>Liters</th>
                                    <th>Cost</th>
                                    <th>KM</th>
                                    <th>Driver</th>
                                    <th>Control #</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuelSlips as $slip)
                                    <tr>
                                        <td>{{ $slip->vehicle_name }}</td>
                                        <td>{{ $slip->plate_number }}</td>
                                        <td>{{ $slip->liters }}</td>
                                        <td>₱{{ number_format($slip->cost, 2) }}</td>
                                        <td>{{ $slip->km_reading }}</td>
                                        <td>{{ $slip->driver }}</td>
                                        <td>{{ $slip->control_number }}</td>
                                        <td>{{ $slip->date }}</td>
                                        <td>
                                            <a href="{{ route('fuel-slips.exportPDF', $slip->id) }}" class="btn-edit">PDF</a>
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

                {{-- Fuel Slips Table --}}
                @if($fuelSlips->count() > 0)
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Plate #</th>
                                    <th>Liters</th>
                                    <th>Cost</th>
                                    <th>KM</th>
                                    <th>Driver</th>
                                    <th>Control #</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuelSlips as $slip)
                                    <tr>
                                        <td>{{ $slip->vehicle_name }}</td>
                                        <td>{{ $slip->plate_number }}</td>
                                        <td>{{ $slip->liters }}</td>
                                        <td>₱{{ number_format($slip->cost, 2) }}</td>
                                        <td>{{ $slip->km_reading }}</td>
                                        <td>{{ $slip->driver }}</td>
                                        <td>{{ $slip->control_number }}</td>
                                        <td>{{ $slip->date }}</td>
                                        <td>
                                            <a href="{{ route('fuel-slips.exportPDF', $slip->id) }}" class="btn-edit">PDF</a>
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
