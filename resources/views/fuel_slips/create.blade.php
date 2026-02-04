@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP SEal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>
        
    </div>

    <div class="dashboard-body">
        <nav class="dashboard-nav">
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </nav>

        <div class="dashboard-container">
            <div class="form-layout">
                <div class="page-header">
                    <h2>Add Fuel Slip</h2>
                    <a href="{{ route('fuel-slips.index') }}" class="btn-primary btn-sm">← Back to Fuel Slips</a>
                </div>

                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="form-block">
                    <form action="{{ route('fuel-slips.store') }}" method="POST">
                        @csrf

                        <label for="vehicle_name">Vehicle Name:</label>
                        <input id="vehicle_name" type="text" name="vehicle_name" required placeholder="Enter vehicle name" value="{{ old('vehicle_name') }}">

                        <label for="plate_number">Plate Number:</label>
                        <input id="plate_number" type="text" name="plate_number" required placeholder="Enter plate number" value="{{ old('plate_number') }}">
                        <p class="form-tip">Tip: Your dashboard counts slips under your account. Make sure plate number matches your registered vehicle.</p>

                        <label for="liters">Liters:</label>
                        <input id="liters" type="number" step="0.01" name="liters" required value="{{ old('liters') }}">

                        <label for="cost">Cost:</label>
                        <input id="cost" type="number" step="0.01" name="cost" required value="{{ old('cost') }}">

                        <label for="km_reading">KM Reading:</label>
                        <input id="km_reading" type="number" name="km_reading" required value="{{ old('km_reading') }}">

                        <label for="driver">Driver:</label>
                        <input id="driver" type="text" name="driver" required value="{{ old('driver') }}">

                        <label for="date">Date:</label>
                        <input id="date" type="date" name="date" required value="{{ old('date') }}">

                        <button type="submit" class="btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
