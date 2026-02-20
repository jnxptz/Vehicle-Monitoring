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
                    <h2>Maintenances</h2>
                    <button onclick="openMaintenanceModal()" class="btn-primary btn-sm">+ Add Maintenance</button>
                </div>

@else
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/splogoo.png') }}" alt="Logo">
                <h1>Sangguniang Panlalawigan</h1>
            </div>

            {{-- Sidebar --}}
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

            {{-- Sidebar --}}
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

            {{-- Main Content --}}
            <div class="dashboard-container">
                <div class="page-header">
                    <h2>Maintenances</h2>
                </div>
@endif

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

                {{-- Admin View: Expandable Boardmembers with Maintenances --}}
                @if(auth()->user()->role === 'admin')
                    @if($boardmembers->count() > 0)
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Board Member</th>
                                        <th>Maintenances</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach($boardmembers as $bm)
                                        @php 
                                            $totalMaint = 0;
                                            foreach($bm->vehicles as $v) {
                                                $totalMaint += $v->maintenances ? count($v->maintenances) : 0;
                                            }
                                        @endphp
                                        <tr class="main-row" onclick="toggleRow('maint-{{ $bm->id }}')" style="cursor:pointer;">
                                            <td>{{ $counter }}</td>
                                            <td>{{ $bm->name }}</td>
                                            <td>{{ $totalMaint }} record(s)</td>
                                        </tr>

                                        <tr id="maint-{{ $bm->id }}-details" class="details-row" style="display:none;">
                                            <td colspan="3">
                                                <div style="overflow-x:auto;">
                                                    @if($totalMaint > 0)
                                                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                                                            @foreach($bm->vehicles as $vehicle)
                                                                @if($vehicle->maintenances && count($vehicle->maintenances) > 0)
                                                                    @foreach($vehicle->maintenances as $m)
                                                                        <div style="border:1px solid #e6eef8; border-radius:8px; padding:16px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                                                                <strong style="font-size:15px;">{{ $vehicle->plate_number }}</strong>
                                                                                <a href="{{ route('maintenances.exportPDF', $m->id) }}" style="background:#ff9b00; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; text-decoration:none;">PDF</a>
                                                                            </div>
                                                                            <div style="font-size:13px; line-height:1.8;">
                                                                                <div><span style="color:#6b7280;">Type:</span> <strong>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</strong></div>
                                                                                <div><span style="color:#6b7280;">KM:</span> {{ $m->maintenance_km ?? '—' }}</div>
                                                                                <div><span style="color:#6b7280;">Operation(s):</span> {{ Str::limit($m->operation, 100, '...') }}</div>
                                                                                <div><span style="color:#6b7280;">Cost:</span> <strong>₱{{ number_format((float) $m->cost, 2) }}</strong></div>
                                                                                <div><span style="color:#6b7280;">Date:</span> {{ $m->date }}</div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div style="padding:12px; color:#6b7280;">No maintenances for this boardmember.</div>
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
                        <p class="empty-message">No maintenance records found.</p>
                    @endif
                @else
                    {{-- Boardmember View: Simple table of their own maintenances --}}
                    @if($maintenances->count() > 0)
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>KM</th>
                                        <th>Operation(s)</th>
                                        <th>Cost</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($maintenances as $m)
                                        <tr>
                                            <td>{{ $m->vehicle->plate_number }}</td>
                                            <td>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</td>
                                            <td>{{ $m->maintenance_km ?? '—' }}</td>
                                            <td>{{ Str::limit($m->operation, 80, '...') }}</td>
                                            <td>₱{{ number_format((float) $m->cost, 2) }}</td>
                                            <td>{{ $m->date }}</td>
                                            <td>
                                                <a href="{{ route('maintenances.exportPDF', $m->id) }}" class="btn-edit">PDF</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="empty-message">No maintenance records found.</p>
                    @endif
                @endif
            </div> {{-- dashboard-container --}}
        </div> {{-- dashboard-body --}}
    </div> {{-- dashboard-page --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const boardmembersData = @json(isset($boardmembers) ? $boardmembers->mapWithKeys(function($bm) { return [$bm->id => $bm->vehicles ?? []]; }) : []);

        // Expose modal functions globally so the inline onclick works
        window.openMaintenanceModal = function() {
            initializeVehicleOptions();
            document.getElementById('maintenanceModal').style.display = 'block';
        };

        window.closeMaintenanceModal = function() {
            document.getElementById('maintenanceModal').style.display = 'none';
        };

        const boardmemberSelect = document.getElementById('boardmember_id');
        const vehicleSelect = document.getElementById('vehicle_id');
        const nameInput = document.getElementById('vehicle_name');
        const plateInput = document.getElementById('plate_number');

        function initializeVehicleOptions() {
            if (!vehicleSelect) return;
            vehicleSelect.innerHTML = '<option value="">-- Select registered vehicle --</option>';

            Object.keys(boardmembersData).forEach(bmId => {
                (boardmembersData[bmId] || []).forEach(v => {
                    const option = document.createElement('option');
                    option.value = v.id;
                    option.setAttribute('data-name', v.vehicle_name || '');
                    option.setAttribute('data-plate', v.plate_number || '');
                    option.setAttribute('data-boardmember', bmId);
                    option.textContent = (v.plate_number || '') + ' — ' + (v.vehicle_name || '');
                    vehicleSelect.appendChild(option);
                });
            });
        }

        function filterVehiclesByBoardmember(boardmemberId){
            if (!vehicleSelect) return;
            const opts = vehicleSelect.querySelectorAll('option');
            opts.forEach(o => {
                if (o.value === '') {
                    o.style.display = 'block';
                } else if (boardmemberId && o.getAttribute('data-boardmember') !== boardmemberId) {
                    o.style.display = 'none';
                } else {
                    o.style.display = '';
                }
            });

            vehicleSelect.disabled = !boardmemberId;
            vehicleSelect.value = '';
            if (nameInput) nameInput.value = '';
            if (plateInput) plateInput.value = '';
        }

        boardmemberSelect && boardmemberSelect.addEventListener('change', function(){
            filterVehiclesByBoardmember(this.value);
        });

        vehicleSelect && vehicleSelect.addEventListener('change', function(){
            if (!nameInput || !plateInput) return;
            const opt = this.options[this.selectedIndex];
            nameInput.value = opt ? (opt.getAttribute('data-name') || '') : '';
            plateInput.value = opt ? (opt.getAttribute('data-plate') || '') : '';
        });

        // Initialize vehicle options on page load
        initializeVehicleOptions();

        // Toggle details row for a boardmember
        window.toggleRow = function(id) {
            const details = document.getElementById(id + '-details');
            if (!details) return;
            if (details.style.display === 'none' || details.style.display === '') {
                details.style.display = 'table-row';
            } else {
                details.style.display = 'none';
            }
        };

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('maintenanceModal');
            if (event.target === modal) {
                window.closeMaintenanceModal();
            }
        });
    });
</script>

<!-- Maintenance Modal -->
<div id="maintenanceModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:5% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:600px; max-height:80vh; overflow-y:auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Add Maintenance</h2>
            <span onclick="closeMaintenanceModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('maintenances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="boardmember_id" style="display:block; margin-bottom:12px; font-weight:600;">Boardmember (select to see their vehicles):</label>
            <select id="boardmember_id" name="boardmember_id" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="">-- Select boardmember --</option>
                @if(isset($boardmembers))
                    @foreach($boardmembers as $bm)
                        <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office?->name ?? 'No Office' }})</option>
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

            <label for="maintenance_type" style="display:block; margin-bottom:12px; font-weight:600;">Maintenance Type:</label>
            <select id="maintenance_type" name="maintenance_type" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">
                <option value="preventive">Preventive</option>
                <option value="repair">Repair</option>
            </select>

            <label for="maintenance_km" style="display:block; margin-bottom:12px; font-weight:600;">Odometer KM (at maintenance):</label>
            <input id="maintenance_km" type="number" name="maintenance_km" min="0" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="e.g., 15000">

            <label for="operation" style="display:block; margin-bottom:12px; font-weight:600;">Operation(s) Done:</label>
            <textarea id="operation" name="operation" rows="3" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="e.g., Change oil, Replace brake pads"></textarea>

            <label for="cost" style="display:block; margin-bottom:12px; font-weight:600;">Cost:</label>
            <input id="cost" type="number" step="0.01" name="cost" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="conduct" style="display:block; margin-bottom:12px; font-weight:600;">Conduct:</label>
            <input id="conduct" type="text" name="conduct" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="e.g., Conducted by / Shop name">

            <label for="date" style="display:block; margin-bottom:12px; font-weight:600;">Date:</label>
            <input id="date" type="date" name="date" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <label for="photo" style="display:block; margin-bottom:12px; font-weight:600;">Photo (optional):</label>
            <input id="photo" type="file" name="photo" accept="image/*" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <p style="font-size:12px; color:#666; margin-bottom:20px;">Note: Call of No. will be generated automatically after saving.</p>

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

@endsection

