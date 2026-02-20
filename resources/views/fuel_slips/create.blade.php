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
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                
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

                        <label for="boardmember_id">Boardmember (select to see their vehicles):</label>
                        <select id="boardmember_id" name="boardmember_id">
                            <option value="">-- Select boardmember --</option>
                            @foreach($boardmembers as $bm)
                                <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office->name ?? 'No Office' }})</option>
                            @endforeach
                        </select>

                        <label for="vehicle_id">Registered Vehicle (optional):</label>
                        <select id="vehicle_id" name="vehicle_id" disabled>
                            <option value="">-- Select registered vehicle --</option>
                            @foreach($boardmembers as $bm)
                                @foreach($bm->office?->vehicles ?? [] as $v)
                                    <option value="{{ $v->id }}" data-name="{{ $v->vehicle_name }}" data-plate="{{ $v->plate_number }}" data-boardmember="{{ $bm->id }}">{{ $v->plate_number }} — {{ $v->vehicle_name }}</option>
                                @endforeach
                            @endforeach
                        </select>

                        <p class="form-tip">Tip: Select a boardmember first to filter their vehicles, then choose a registered vehicle to auto-fill vehicle name and plate number, or leave blank to enter new details.</p>

                        <label for="vehicle_name">Vehicle Name:</label>
                        <input id="vehicle_name" type="text" name="vehicle_name" placeholder="Enter vehicle name" value="{{ old('vehicle_name') }}">

                        <label for="plate_number">Plate Number:</label>
                        <input id="plate_number" type="text" name="plate_number" placeholder="Enter plate number" value="{{ old('plate_number') }}">

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

                        <label for="prepared_by_name">Prepared by Name:</label>
                        <input id="prepared_by_name" type="text" name="prepared_by_name" placeholder="Enter name of person who prepared" value="{{ old('prepared_by_name') }}">

                        <label for="approved_by_name">Approved by Name:</label>
                        <input id="approved_by_name" type="text" name="approved_by_name" placeholder="Enter name of person who approved" value="{{ old('approved_by_name') }}">

                        <button type="submit" class="btn-primary">Submit</button>
                    </form>

                    <script>
                        (function(){
                            const boardmemberSelect = document.getElementById('boardmember_id');
                            const vehicleSelect = document.getElementById('vehicle_id');
                            const nameInput = document.getElementById('vehicle_name');
                            const plateInput = document.getElementById('plate_number');

                            function filterVehiclesByBoardmember(boardmemberId){
                                const opts = vehicleSelect.querySelectorAll('option[data-boardmember]');
                                opts.forEach(o => {
                                    if(boardmemberId && o.getAttribute('data-boardmember') !== boardmemberId) {
                                        o.style.display = 'none';
                                    } else {
                                        o.style.display = '';
                                    }
                                });

                                // enable select only when a boardmember is chosen
                                vehicleSelect.disabled = !boardmemberId;
                                vehicleSelect.value = '';
                                nameInput.value = '';
                                plateInput.value = '';
                            }

                            boardmemberSelect && boardmemberSelect.addEventListener('change', function(){
                                filterVehiclesByBoardmember(this.value);
                            });

                            vehicleSelect && vehicleSelect.addEventListener('change', function(){
                                const opt = this.options[this.selectedIndex];
                                const name = opt.getAttribute('data-name') || '';
                                const plate = opt.getAttribute('data-plate') || '';

                                if(name) nameInput.value = name;
                                if(plate) plateInput.value = plate;
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
