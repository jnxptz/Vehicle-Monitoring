@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>
        
    </div>

    <div class="dashboard-body">
        <nav class="dashboard-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
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
                    <h2>Add Maintenance</h2>
                    <a href="{{ route('maintenances.index') }}" class="btn-primary btn-sm">← Back to Maintenances</a>
                </div>

                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="form-block">
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

                        <p class="form-tip">Note: Call of No. will be generated automatically after saving.</p>

                        <label for="date">Date:</label>
                        <input id="date" type="date" name="date" required value="{{ old('date') }}">

                        <label for="photo">Photo (optional):</label>
                        <input id="photo" type="file" name="photo" accept="image/*">

                        <button type="submit" class="btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

