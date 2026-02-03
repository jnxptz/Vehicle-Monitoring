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
            <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <a href="{{ route('vehicles.create') }}" class="{{ request()->routeIs('vehicles.create') ? 'active' : '' }}">Register Vehicle</a>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
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

                    <label for="plate_number">Plate Number:</label>
                    <input
                        id="plate_number"
                        type="text"
                        name="plate_number"
                        required
                        value="{{ old('plate_number') }}"
                        placeholder="Enter plate number"
                    >

                    <label for="monthly_fuel_limit">
                        Monthly Fuel Limit (liters):
                    </label>
                    <input
                        id="monthly_fuel_limit"
                        type="number"
                        name="monthly_fuel_limit"
                        required
                        min="1"
                        step="0.01"
                        value="{{ old('monthly_fuel_limit', 100) }}"
                    >

                    <button type="submit">Register Vehicle</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
