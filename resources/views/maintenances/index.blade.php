@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@if(auth()->user()->role === 'admin')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('css/maintenances-styles.css') }}">

@if(auth()->user()->role === 'admin')
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
                <h1>Sangguniang Panlalawigan</h1>
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
                    
                    <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                    <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                    <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                    <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
                    <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Boardmembers</a>
                    <div class="logout-form">
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
                
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
                <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}">Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}">Manage Users</a>
                <div class="logout-form">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </nav>

            {{-- Main Content --}}
            <div class="dashboard-container">
                <div class="page-header">
                    <div>
                        <h2>Maintenances</h2>
                        <p class="sub-text">Manage vehicle maintenance records</p>
                    </div>
                    
                    <form method="GET" action="{{ route('maintenances.index') }}" class="filter-bar" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <select name="office" onchange="this.form.submit()" style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #ffffff; color: #1e293b; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                            <option value="">All Offices</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" {{ request('office') == $office->id ? 'selected' : '' }}>
                                    {{ $office->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" onclick="openMaintenanceModal()" class="btn-primary btn-sm" style="padding: 10px 20px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(139, 92, 246, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(139, 92, 246, 0.2)';">+ Add Maintenance</button>
                    </form>
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
                    <div class="logout-form">
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
                <div class="logout-form">
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
                        <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse; border: none;">
                                <thead>
                                    <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                                        <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">#</th>
                                        <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Board Member</th>
                                        <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Maintenances</th>
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
                                        <tr class="main-row" onclick="toggleRow('maint-{{ $bm->id }}')" data-loop-even="{{ $loop->even }}">
                                            <td class="counter-cell">{{ $counter }}</td>
                                            <td class="boardmember-cell">{{ $bm->name }}</td>
                                            <td class="maintenance-count-cell">
                                                <span class="maintenance-count-badge">
                                                    {{ $totalMaint }} record(s)
                                                </span>
                                            </td>
                                        </tr>

                                        <tr id="maint-{{ $bm->id }}-details" class="details-row">
                                            <td colspan="3">
                                                <div class="maintenance-cards-container">
                                                    @if($totalMaint > 0)
                                                        <div class="maintenance-cards-grid">
                                                            @foreach($bm->vehicles as $vehicle)
                                                                @if($vehicle->maintenances && count($vehicle->maintenances) > 0)
                                                                    @foreach($vehicle->maintenances as $m)
                                                                        <div class="maintenance-card">
                                                                            <div class="maintenance-card-header">
                                                                                <strong>{{ $vehicle->plate_number }}</strong>
                                                                                <a href="{{ route('maintenances.exportPDF', $m->id) }}" class="pdf-btn">PDF</a>
                                                                            </div>
                                                                            <div class="maintenance-details">
                                                                                <div><span class="detail-label">Type:</span> <strong>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</strong></div>
                                                                                <div><span class="detail-label">KM:</span> {{ $m->maintenance_km ?? '—' }}</div>
                                                                                <div><span class="detail-label">Operation(s):</span> {{ Str::limit($m->operation, 100, '...') }}</div>
                                                                                <div><span class="detail-label">Cost:</span> <strong>₱{{ number_format((float) $m->cost, 2) }}</strong></div>
                                                                                <div><span class="detail-label">Date:</span> {{ $m->date }}</div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="no-maintenances-message">No maintenances for this boardmember.</div>
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
                            <table class="boardmember-maintenances-table">
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
                                        <tr class="boardmember-maintenance-row" data-loop-even="{{ $loop->even }}">
                                            <td class="vehicle-cell">{{ $m->vehicle->plate_number }}</td>
                                            <td class="type-cell">{{ ucfirst($m->maintenance_type ?? 'preventive') }}</td>
                                            <td class="km-cell">{{ $m->maintenance_km ?? '—' }}</td>
                                            <td class="operation-cell">{{ Str::limit($m->operation, 80, '...') }}</td>
                                            <td class="cost-cell">₱{{ number_format((float) $m->cost, 2) }}</td>
                                            <td class="date-cell">{{ $m->date }}</td>
                                            <td class="actions-cell">
                                                <a href="{{ route('maintenances.exportPDF', $m->id) }}" class="pdf-btn">PDF</a>
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
    window.boardmembersData = @json(isset($boardmembers) ? $boardmembers->mapWithKeys(function($bm) { return [$bm->id => $bm->vehicles ?? []]; }) : []);
</script>
<script src="{{ asset('js/maintenances.js') }}"></script>

<!-- Maintenance Modal -->
<div id="maintenanceModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); padding: 32px; border: 1px solid #e2e8f0; width:90%; max-width:600px; max-height:80vh; overflow-y:auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <h2 style="margin:0; color: #1e40af; font-size: 20px; font-weight: 600;">Add Maintenance Record</h2>
            <span onclick="closeMaintenanceModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer; position: absolute; top: 16px; right: 16px;">&times;</span>
        </div>

        <form action="{{ route('maintenances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Boardmember Selection -->
                <div>
                    <label for="boardmember_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Select Boardmember</label>
                    <div style="position: relative;">
                        <select id="boardmember_id" name="boardmember_id" style="
                            width: 100%;
                            padding: 12px 16px;
                            border: 2px solid #e2e8f0;
                            border-radius: 8px;
                            background: #ffffff;
                            font-size: 14px;
                            color: #1e293b;
                            appearance: none;
                            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http://www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%2010l5%205%205%205z%22%20fill%3D%22%236b7280%22/%3E%3C/svg%3E');
                            background-repeat: no-repeat;
                            background-position: right 12px center;
                            background-size: 20px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        ">
                            <option value="">Choose a boardmember...</option>
                            @if(isset($boardmembers))
                                @foreach($boardmembers as $bm)
                                    <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office?->name ?? 'No Office' }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Vehicle Selection -->
                <div>
                    <label for="vehicle_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Select Vehicle</label>
                    <div style="position: relative;">
                        <select id="vehicle_id" name="vehicle_id" style="
                            width: 100%;
                            padding: 12px 16px;
                            border: 2px solid #e2e8f0;
                            border-radius: 8px;
                            background: #ffffff;
                            font-size: 14px;
                            color: #1e293b;
                            appearance: none;
                            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http://www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%2010l5%205%205%205z%22%20fill%3D%22%236b7280%22/%3E%3C/svg%3E');
                            background-repeat: no-repeat;
                            background-position: right 12px center;
                            background-size: 20px;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        ">
                            <option value="">Choose a vehicle...</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Maintenance Type -->
                <div>
                    <label for="maintenance_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Maintenance Type</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff';">
                            <input type="radio" name="maintenance_type" value="preventive" style="margin-right: 8px;">
                            <span style="font-weight: 500;">Preventive</span>
                        </label>
                        <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff';">
                            <input type="radio" name="maintenance_type" value="repair" style="margin-right: 8px;">
                            <span style="font-weight: 500;">Repair</span>
                        </label>
                    </div>
                </div>

                <!-- Odometer Reading -->
                <div>
                    <label for="maintenance_km" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Odometer Reading</label>
                    <div style="position: relative;">
                        <input id="maintenance_km" type="number" name="maintenance_km" min="0" required style="
                            width: 100%;
                            padding: 12px 16px;
                            border: 2px solid #e2e8f0;
                            border-radius: 8px;
                            font-size: 16px;
                            font-weight: 500;
                            background: #ffffff;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                    </div>
                </div>

                <!-- Cost -->
                <div>
                    <label for="cost" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Cost</label>
                    <div style="position: relative;">
                        <input id="cost" type="number" step="0.01" name="cost" required style="
                            width: 100%;
                            padding: 12px 16px;
                            border: 2px solid #e2e8f0;
                            border-radius: 8px;
                            font-size: 16px;
                            font-weight: 500;
                            background: #ffffff;
                            transition: all 0.2s ease;
                        " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                    </div>
                </div>
            </div>

            <!-- Operations Done -->
            <div style="margin-bottom: 20px;">
                <label for="operation" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Operations Performed</label>
                <textarea id="operation" name="operation" rows="4" required placeholder="Describe the maintenance operations performed..." style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 2px solid #e2e8f0;
                    border-radius: 8px;
                    font-size: 14px;
                    font-family: inherit;
                    resize: vertical;
                    background: #ffffff;
                    transition: all 0.2s ease;
                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"></textarea>
            </div>

            <!-- Conduct Details -->
            <div style="margin-bottom: 20px;">
                <label for="conduct" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Conducted By</label>
                <input id="conduct" type="text" name="conduct" required placeholder="Mechanic name or service center" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 2px solid #e2e8f0;
                    border-radius: 8px;
                    font-size: 14px;
                    background: #ffffff;
                    transition: all 0.2s ease;
                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <!-- Date -->
                <div>
                    <label for="date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Date</label>
                    <input id="date" type="date" name="date" required value="{{ old('date') ?? now()->format('Y-m-d') }}" style="
                        width: 100%;
                        padding: 12px 16px;
                        border: 2px solid #e2e8f0;
                        border-radius: 8px;
                        font-size: 14px;
                        background: #ffffff;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                </div>

                <!-- Photo Upload -->
                <div>
                    <label for="photo" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Photo (Optional)</label>
                    <div style="
                        border: 2px dashed #cbd5e1;
                        border-radius: 8px;
                        padding: 24px;
                        text-align: center;
                        background: #f8fafc;
                        transition: all 0.2s ease;
                        cursor: pointer;
                    " onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onclick="document.getElementById('photo').click()">
                        <input id="photo" type="file" name="photo" accept="image/*" style="display: none;" onchange="
                            const file = this.files[0];
                            if (file) {
                                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                                
                                if (file.size > maxSize) {
                        // Show error message instead of displaying the photo
                        document.getElementById('modal-photo-name').textContent = 'Error: File too large (Max 5MB)';
                        document.getElementById('modal-photo-name').style.color = '#dc2626';
                        this.value = ''; // Clear the input
                    } else {
                        document.getElementById('modal-photo-name').textContent = file.name;
                        document.getElementById('modal-photo-name').style.color = '#1e293b';
                    }
                            }
                        ">
                        <div style="margin-bottom: 8px;">
                            <div id="modal-photo-name" style="color: #1e293b; font-weight: 500;">Choose a photo or drag & drop</div>
                        </div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 8px;">
                            Supported formats: JPG, PNG, GIF (Max 5MB)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="color: #64748b; font-size: 14px;">
                    <div style="display: flex; align-items: center; margin-bottom: 4px;">
                        <span style="color: #059669; margin-right: 4px;">Info:</span>
                        Call of No. will be automatically generated
                    </div>
                    <div style="font-size: 12px;">
                        Format: MN-YYYYMMDD-XXXXXX
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="
                    padding: 14px 32px;
                    font-size: 16px;
                    font-weight: 600;
                    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
                " onmouseover="this.style.background='linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(139, 92, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.2)';">
                    Create Maintenance Record
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
