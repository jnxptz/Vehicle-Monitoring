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
        <h2>Fuel Slips</h2>

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

        @if(auth()->user()->role === 'boardmember')
            <div class="action-header">
                <a href="{{ route('fuel-slips.create') }}" class="btn-primary">+ Add Fuel Slip</a>
            </div>
        @endif

        @if($fuelSlips->count() > 0)
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
        @else
            <p class="empty-message">No fuel slips found.</p>
        @endif
    </div>
</div>
@endsection
