@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

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

    <div class="form-card">
        <h2>Add Maintenance</h2>

        @if ($errors->any())
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('maintenances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="vehicle_id">Vehicle (Plate Number):</label>
            <select id="vehicle_id" name="vehicle_id" required>
                <option value="">-- Select vehicle --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                        {{ $vehicle->plate_number }}
                    </option>
                @endforeach
            </select>

            <label for="maintenance_type">Maintenance Type:</label>
            <select id="maintenance_type" name="maintenance_type" required>
                <option value="preventive" {{ old('maintenance_type', 'preventive') === 'preventive' ? 'selected' : '' }}>Preventive</option>
                <option value="repair" {{ old('maintenance_type') === 'repair' ? 'selected' : '' }}>Repair</option>
            </select>

            <label for="maintenance_km">Odometer KM (at maintenance):</label>
            <input id="maintenance_km" type="number" name="maintenance_km" min="0" required value="{{ old('maintenance_km') }}" placeholder="e.g., 15000">

            <label for="operation">Operation(s) Done:</label>
            <textarea id="operation" name="operation" rows="3" required placeholder="e.g., Change oil, Replace brake pads">{{ old('operation') }}</textarea>

            <label for="cost">Cost:</label>
            <input id="cost" type="number" step="0.01" name="cost" required value="{{ old('cost') }}">

            <label for="conduct">Conduct:</label>
            <input id="conduct" type="text" name="conduct" required value="{{ old('conduct') }}" placeholder="e.g., Conducted by / Shop name">

            <p style="margin: 6px 0 0; font-size: 12px; color: #607d8b;">
                Call of No. will be generated automatically after saving.
            </p>

            <label for="date">Date:</label>
            <input id="date" type="date" name="date" required value="{{ old('date') }}">

            <label for="photo">Photo (optional):</label>
            <input id="photo" type="file" name="photo" accept="image/*">

            <button type="submit">Submit</button>
        </form>
    </div>
</div>
@endsection

