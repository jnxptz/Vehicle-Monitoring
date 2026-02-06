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

            {{-- Main Content --}}
            <div class="dashboard-container">
                <div class="page-header">
                    <h2>Maintenances</h2>
                    <a href="{{ route('maintenances.create') }}" class="btn-primary btn-sm">+ Add Maintenance</a>
                </div>

@else
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/splogoo.png') }}" alt="Logo">
                <h1>Sangguniang Panlalawigan</h1>
            </div>
            
        </div>

        <div class="dashboard-body">

            {{-- Sidebar --}}
            <nav class="dashboard-nav">
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </nav>
            </nav>

            {{-- Main Content --}}
            <div class="dashboard-container">
                <div class="page-header">
                    <h2>Maintenances</h2>
                </div>
@endif

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

                @if($maintenances->count() > 0)
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>KM</th>
                                    <th>Operation(s)</th>
                                    <th>Cost</th>
                                    <th>Conduct</th>
                                    <th>Call of No.</th>
                                    <th>Date</th>
                                    <th>Photo</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $m)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $m->vehicle?->plate_number ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</td>
                                        <td>{{ $m->maintenance_km ?? '—' }}</td>
                                        <td style="max-width: 320px; white-space: normal;">{{ $m->operation }}</td>
                                        <td>₱{{ number_format((float) $m->cost, 2) }}</td>
                                        <td>{{ $m->conduct }}</td>
                                        <td>{{ $m->call_of_no }}</td>
                                        <td>{{ $m->date }}</td>
                                        <td>
                                            @if($m->photo)
                                                <img src="{{ asset('storage/' . $m->photo) }}" alt="Maintenance photo" style="width:70px; height:70px; object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;">
                                            @else
                                                <span style="color:#777;">No photo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('maintenances.exportPDF', $m->id) }}" class="btn-edit">PDF</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="empty-message">No maintenance records found.</p>
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

