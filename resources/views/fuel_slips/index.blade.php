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

            {{-- Sidebar 
            <div class="hamburger-menu-wrapper">
                <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
                <label for="hamburger-toggle" class="hamburger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <nav class="hamburger-dropdown">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
                    <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                    <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                    <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                    <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Boardmembers</a>
                    <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </nav>
            </div>--}}
        </div>

        <div class="dashboard-body">

        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
            <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>
            <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <h2>Fuel Slips</h2>
                <button onclick="openFuelSlipModal()" class="btn-primary btn-sm">+ Add Fuel Slip</button>
            </div>

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

                {{-- Fuel Slips Table --}}
                @if($boardmembers && $boardmembers->count() > 0)
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Board Member</th>
                                    <th>Fuel Slips</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($boardmembers as $bm)
                                    <tr class="main-row" onclick="toggleRow('fs-{{ $bm->id }}')" style="cursor:pointer;">
                                        <td>{{ $counter }}</td>
                                        <td>{{ $bm->name }}</td>
                                        <td>{{ $bm->fuelSlips->count() }} slip(s)</td>
                                    </tr>

                                    <tr id="fs-{{ $bm->id }}-details" class="details-row" style="display:none;">
                                        <td colspan="3">
                                            <div style="overflow-x:auto;">
                                                @if($bm->fuelSlips->count() > 0)
                                                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                                                        @foreach($bm->fuelSlips as $slip)
                                                            <div style="border:1px solid #e6eef8; border-radius:8px; padding:16px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                                                    <strong style="font-size:15px;">{{ $slip->vehicle_name }}</strong>
                                                                    <a href="{{ route('fuel-slips.exportPDF', $slip->id) }}" style="background:#ff9b00; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; text-decoration:none;">PDF</a>
                                                                </div>
                                                                <div style="font-size:13px; line-height:1.8;">
                                                                    <div><span style="color:#6b7280;">Plate #:</span> <strong>{{ $slip->plate_number }}</strong></div>
                                                                    <div><span style="color:#6b7280;">Liters:</span> {{ $slip->liters }}</div>
                                                                    <div><span style="color:#6b7280;">Cost:</span> <strong>₱{{ number_format($slip->cost, 2) }}</strong></div>
                                                                    <div><span style="color:#6b7280;">KM:</span> {{ $slip->km_reading }}</div>
                                                                    <div><span style="color:#6b7280;">Driver:</span> {{ $slip->driver }}</div>
                                                                    <div><span style="color:#6b7280;">Date:</span> {{ $slip->date }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div style="padding:12px; color:#6b7280;">No fuel slips for this boardmember.</div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    @php $counter++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="empty-message">No fuel slips found.</p>
                @endif
            </div> {{-- dashboard-container --}}
        </div> {{-- dashboard-body --}}
    </div> {{-- dashboard-page --}}
@else
    {{-- Boardmember View --}}
    <div class="dashboard-page">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
                <h1>Sangguniang Panlalawigan</h1>
            </div>

            {{-- Hamburger Menu (Mobile Only) --}}
            <div class="hamburger-menu-wrapper">
                <input type="checkbox" id="hamburger-toggle" class="hamburger-toggle">
                <label for="hamburger-toggle" class="hamburger-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                <nav class="hamburger-dropdown">
                    <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                    <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                    <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="dashboard-body">

            <nav class="dashboard-nav">
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
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
                <div class="page-header">
                    <h2>Fuel Slips</h2>
                </div>

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

                {{-- Fuel Slips Table (Boardmember View) --}}
                @if($fuelSlips && $fuelSlips->count() > 0)
                    <div class="table-wrapper">
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
                    </div>
                @else
                    <p class="empty-message">No fuel slips found.</p>
                @endif
            </div> {{-- dashboard-container --}}
        </div> {{-- dashboard-body --}}
    </div> {{-- dashboard-page --}}
@endif

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

    // Fuel Slip Modal functions
    function openFuelSlipModal() {
        document.getElementById('fuelSlipModal').style.display = 'block';
    }

    function closeFuelSlipModal() {
        document.getElementById('fuelSlipModal').style.display = 'none';
    }

    // Toggle details row for a boardmember
    function toggleRow(id) {
        const details = document.getElementById(id + '-details');
        if (!details) return;
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'table-row';
        } else {
            details.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        const modal = document.getElementById('fuelSlipModal');
        if (event.target === modal) {
            closeFuelSlipModal();
        }
    }
</script>

<!-- Fuel Slip Modal -->
<div id="fuelSlipModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:5% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:600px; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Add Fuel Slip</h2>
            <span onclick="closeFuelSlipModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('fuel-slips.store') }}" method="POST">
            @csrf

            <label for="boardmember_id" style="display:block; margin-bottom:12px; font-weight:600;">Boardmember (select to see their vehicles):</label>
            <select id="boardmember_id" name="boardmember_id" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="">-- Select boardmember --</option>
                @if(isset($boardmembers))
                    @foreach($boardmembers as $bm)
                        <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office->name ?? 'No Office' }})</option>
                    @endforeach
                @endif
            </select>

            <label for="vehicle_id" style="display:block; margin-bottom:12px; font-weight:600;">Registered Vehicle (optional):</label>
            <select id="vehicle_id" name="vehicle_id" disabled style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="">-- Select registered vehicle --</option>
            </select>

            <p style="font-size:12px; color:#666; margin-bottom:20px;">Tip: Select a boardmember first to filter their vehicles, then choose a registered vehicle to auto-fill vehicle name and plate number, or leave blank to enter new details.</p>

            <label for="vehicle_name" style="display:block; margin-bottom:12px; font-weight:600;">Vehicle Name:</label>
            <input id="vehicle_name" type="text" name="vehicle_name" placeholder="Enter vehicle name" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="plate_number" style="display:block; margin-bottom:12px; font-weight:600;">Plate Number:</label>
            <input id="plate_number" type="text" name="plate_number" placeholder="Enter plate number" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="liters" style="display:block; margin-bottom:12px; font-weight:600;">Liters:</label>
            <input id="liters" type="number" step="0.01" name="liters" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="cost" style="display:block; margin-bottom:12px; font-weight:600;">Cost:</label>
            <input id="cost" type="number" step="0.01" name="cost" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="km_reading" style="display:block; margin-bottom:12px; font-weight:600;">KM Reading:</label>
            <input id="km_reading" type="number" name="km_reading" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="driver" style="display:block; margin-bottom:12px; font-weight:600;">Driver:</label>
            <input id="driver" type="text" name="driver" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="date" style="display:block; margin-bottom:12px; font-weight:600;">Date:</label>
            <input id="date" type="date" name="date" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="prepared_by_name" style="display:block; margin-bottom:12px; font-weight:600;">Prepared by Name:</label>
            <input id="prepared_by_name" type="text" name="prepared_by_name" placeholder="Enter name of person who prepared" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="approved_by_name" style="display:block; margin-bottom:12px; font-weight:600;">Approved by Name:</label>
            <input id="approved_by_name" type="text" name="approved_by_name" placeholder="Enter name of person who approved" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Submit</button>
        </form>

        @if ($errors->any())
            <div style="margin-top:20px; background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:12px; border-radius:4px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
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

        vehicleSelect.disabled = !boardmemberId;
        vehicleSelect.value = '';
        nameInput.value = '';
        plateInput.value = '';
    }

    boardmemberSelect && boardmemberSelect.addEventListener('change', function(){
        filterVehiclesByBoardmember(this.value);
    });

    vehicleSelect && vehicleSelect.addEventListener('change', function(){
        nameInput.value = this.options[this.selectedIndex].getAttribute('data-name') || '';
        plateInput.value = this.options[this.selectedIndex].getAttribute('data-plate') || '';
    });

    // Populate vehicle options on page load
    const boardmembersData = @json(isset($boardmembers) ? $boardmembers->mapWithKeys(function($bm) { return [$bm->id => $bm->vehicles ?? []]; }) : []);
    
    Object.keys(boardmembersData).forEach(bmId => {
        boardmembersData[bmId].forEach(v => {
            const option = document.createElement('option');
            option.value = v.id;
            option.setAttribute('data-name', v.vehicle_name);
            option.setAttribute('data-plate', v.plate_number);
            option.setAttribute('data-boardmember', bmId);
            option.textContent = v.plate_number + ' — ' + v.vehicle_name;
            vehicleSelect.appendChild(option);
        });
    });
</script>

@endsection
