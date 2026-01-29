@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

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
            <input id="plate_number" type="text" name="plate_number" required value="{{ old('plate_number') }}" placeholder="Enter plate number">

            <label for="monthly_fuel_limit">Monthly Fuel Limit (liters):</label>
            <input id="monthly_fuel_limit" type="number" name="monthly_fuel_limit" required value="{{ old('monthly_fuel_limit', 100) }}" min="1" step="0.01">

            <button type="submit">Register Vehicle</button>
        </form>
    </div>
</div>
@endsection
