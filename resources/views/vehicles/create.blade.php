@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

<div class="dashboard-page">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>
    </div>

    <div class="dashboard-body">
        {{-- Sidebar --}}
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('vehicles.create') }}" class="{{ request()->routeIs('vehicles.create') ? 'active' : '' }}">Register Vehicle</a>
            @endif
            <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="form-card">
                <h2>Register Vehicle</h2>

                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf

                    @if(isset($boardmembers) && auth()->user()->role === 'admin')
                        <label for="boardmember_id">Boardmember:</label>
                        <select id="boardmember_id" name="boardmember_id" required>
                            <option value="">-- Select Boardmember --</option>
                            @foreach($boardmembers as $boardmember)
                                <option value="{{ $boardmember->id }}">{{ $boardmember->name }} ({{ $boardmember->office->name ?? 'No Office' }})</option>
                            @endforeach
                        </select>
                    @endif

                    <label for="vehicle_name">Vehicle Name:</label>
                    <input
                        id="vehicle_name"
                        type="text"
                        name="vehicle_name"
                        required
                        value="{{ old('vehicle_name') }}"
                        placeholder="e.g., Toyota Corolla"
                    >

                    <label for="plate_number">Plate Number:</label>
                    <input
                        id="plate_number"
                        type="text"
                        name="plate_number"
                        required
                        value="{{ old('plate_number') }}"
                        placeholder="e.g., ABC 1234"
                    >

                    <label for="driver">Driver Name:</label>
                    <input
                        id="driver"
                        type="text"
                        name="driver"
                        required
                        value="{{ old('driver') }}"
                        placeholder="Enter driver name"
                    >

                    <button type="submit">Register Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
