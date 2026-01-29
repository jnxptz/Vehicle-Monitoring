@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@if(auth()->user()->role === 'admin')
    <div class="admin-page">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/splogoo.png') }}" alt="Logo" style="height:64px;">
                <h1>Admin Dashboard</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-logo"><img src="{{ asset('images/splogoo.png') }}" alt="Logo"></a>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
        </nav>
@else
    <div class="dashboard-page">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/splogoo.png') }}" alt="Logo" style="height:64px;">
                <h1>Sangguniang Panlalawigan</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <nav class="dashboard-nav">
            <a href="{{ route('boardmember.dashboard') }}" class="nav-logo"><img src="{{ asset('images/splogoo.png') }}" alt="Logo"></a>
            <a href="{{ route('boardmember.dashboard') }}">Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
        </nav>
@endif

    <div class="content-card">
        <h2>Maintenances</h2>

        @if(auth()->user()->role === 'admin')
            <div class="action-header">
                <a href="{{ route('maintenances.create') }}" class="btn-primary">+ Add Maintenance</a>
            </div>
        @endif

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if($maintenances->count() > 0)
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
                            <td>
                                {{ $m->vehicle?->plate_number ?? 'N/A' }}
                            </td>
                            <td>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</td>
                            <td>{{ $m->maintenance_km ?? '—' }}</td>
                            <td style="max-width: 320px;">
                                {{ $m->operation }}
                            </td>
                            <td>₱{{ number_format((float) $m->cost, 2) }}</td>
                            <td>{{ $m->conduct }}</td>
                            <td>{{ $m->call_of_no }}</td>
                            <td>{{ $m->date }}</td>
                            <td>
                                @if($m->photo)
                                    <img
                                        src="{{ asset('storage/' . $m->photo) }}"
                                        alt="Maintenance photo"
                                        style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #e0e0e0;"
                                    >
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
        @else
            <p class="empty-message">No maintenance records found.</p>
        @endif
    </div>
</div>
@endsection

