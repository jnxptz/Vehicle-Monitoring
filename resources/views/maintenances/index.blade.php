@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@if(auth()->user()->role === 'admin')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('css/maintenances-styles.css') }}">

<style>
    /* Mobile responsive table styles (match fuel slips) */
    @media (max-width: 768px) {
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-wrapper table {
            min-width: 600px;
            font-size: 12px;
        }

        .table-wrapper th {
            padding: 8px 4px !important;
            font-size: 11px !important;
            white-space: nowrap;
        }

        .table-wrapper td {
            padding: 6px 4px !important;
            font-size: 11px !important;
            word-wrap: break-word;
        }

        .details-row div[style*="display:grid"] {
            grid-template-columns: 1fr !important;
            gap: 8px !important;
        }
    }
</style>

@if(auth()->user()->role === 'admin')
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
                <h1>Vehicle Monitoring System</h1>
            </div>

            @include('partials.user-profile-dropdown')

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
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>

                <div class="bottom-section">
                    <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                    <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                </div>

                <div class="logout-form">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
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

                        <button type="button" onclick="openMaintenanceModal()" class="btn-primary btn-sm" style="padding: 10px 20px; background: linear-gradient(135deg, #ff9b00 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(255, 155, 0, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 155, 0, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #ff9b00 0%, #d97706 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(255, 155, 0, 0.2)';">+ Add Maintenance</button>
                    </form>
                </div>

@else
    <div class="dashboard-page">
        {{-- Header --}}
        <div class="dashboard-header">
            <div class="dashboard-title">
                <img src="{{ asset('images/splogoo.png') }}" alt="Logo">
                <h1>Vehicle Monitoring System</h1>
            </div>

            @include('partials.user-profile-dropdown')

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
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                <div class="bottom-section">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
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
                                    <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
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
                                        <tr class="main-row" onclick="toggleRow('maint-{{ $bm->id }}')" style="cursor:pointer; background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                            <td style="padding: 16px 20px; font-weight: 500; color: #1e40af; border: none;">{{ $counter }}</td>
                                            <td style="padding: 16px 20px; font-weight: 500; color: #1e293b; border: none;">{{ $bm->name }}</td>
                                            <td style="padding: 16px 20px; color: #64748b; border: none;">
                                                <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                                    {{ $totalMaint }} record(s)
                                                </span>
                                            </td>
                                        </tr>

                                        <tr id="maint-{{ $bm->id }}-details" class="details-row" style="display:none; background: #ffffff;">
                                            <td colspan="3" style="padding: 0; border: none;">
                                                <div class="maintenance-cards-container" style="overflow-x:auto;">
                                                    @if($totalMaint > 0)
                                                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
                                                            @foreach($bm->vehicles as $vehicle)
                                                                @if($vehicle->maintenances && count($vehicle->maintenances) > 0)
                                                                    @foreach($vehicle->maintenances as $m)
                                                                        <div style="border:1px solid #e6eef8; border-radius:8px; padding:16px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                                                                <strong style="font-size:15px;">{{ $vehicle->plate_number }}</strong>
                                                                                <div style="display:flex; gap:6px;">
                                                                                <a href="{{ route('maintenances.exportPDF', $m->id) }}" style="background:#ff9b00; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; text-decoration:none;">PDF</a>
                                                                                <a href="javascript:void(0)" onclick="openPDFModal({{ $m->id }})" style="background:#3b82f6; color:white; border:none; padding:4px 10px; border-radius:4px; cursor:pointer; font-size:12px; font-weight:600; text-decoration:none;">View</a>
                                                                            </div>
                                                                            </div>
                                                                            <div style="font-size:13px; line-height:1.8;">
                                                                                <div><span style="color:#6b7280;">Type:</span> <strong>{{ ucfirst($m->maintenance_type ?? 'preventive') }}</strong></div>
                                                                                <div><span style="color:#6b7280;">KM:</span> {{ $m->maintenance_km ?? '—' }}</div>
                                                                                <div><span style="color:#6b7280;">Operation(s):</span> {{ Str::limit($m->operation, 100, '...') }}</div>
                                                                                <div><span style="color:#6b7280;">Cost:</span> <strong>₱{{ number_format((float) $m->cost, 2) }}</strong></div>
                                                                                <div><span style="color:#6b7280;">Date:</span> {{ $m->date }}</div>
                                                                                @if($m->prepared_by_name || $m->approved_by_name)
                                                                                <div><span style="color:#6b7280;">Prepared by:</span> {{ $m->prepared_by_name ?? '—' }}</div>
                                                                                <div><span style="color:#6b7280;">Approved by:</span> {{ $m->approved_by_name ?? '—' }}</div>
                                                                                @endif
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
                    {{-- Boardmember View: Simple table of their own maintenances (same design as fuel slips) --}}
                    @if($maintenances->count() > 0)
                        <div class="table-wrapper" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
                            <table style="width: 100%; border-collapse: collapse; border: none;">
                                <thead>
                                    <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Vehicle</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Type</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">KM</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Operation(s)</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Cost</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Date</th>
                                        <th style="padding: 14px 16px; text-align: left; color: #ffffff; font-weight: 600; font-size: 13px; border: none;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($maintenances as $m)
                                        <tr style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                            <td style="padding: 14px 16px; font-weight: 500; color: #1e293b; border: none;">{{ $m->vehicle->plate_number }}</td>
                                            <td style="padding: 14px 16px; color: #64748b; border: none;">{{ ucfirst($m->maintenance_type ?? 'preventive') }}</td>
                                            <td style="padding: 14px 16px; color: #64748b; border: none;">{{ $m->maintenance_km ?? '—' }}</td>
                                            <td style="padding: 14px 16px; color: #64748b; border: none;">{{ Str::limit($m->operation, 80, '...') }}</td>
                                            <td style="padding: 14px 16px; font-weight: 600; color: #059669; border: none;">₱{{ number_format((float) $m->cost, 2) }}</td>
                                            <td style="padding: 14px 16px; color: #64748b; border: none; font-size: 13px;">{{ $m->date }}</td>
                                            <td style="padding: 14px 16px; border: none;">
                                                <div style="display:flex; gap:6px;">
                                                    <a href="{{ route('maintenances.exportPDF', $m->id) }}" style="background: #ff9b00; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block;">PDF</a>
                                                    <a href="javascript:void(0)" onclick="openPDFModal({{ $m->id }})" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-block;">View</a>
                                                </div>
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

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
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
                <!-- Prepared By -->
                <div>
                    <label for="modal_prepared_by_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Prepared By</label>
                    <input id="modal_prepared_by_name" type="text" name="prepared_by_name" placeholder="" value="{{ old('prepared_by_name') }}" style="
                        width: 100%;
                        padding: 12px 16px;
                        border: 2px solid #e2e8f0;
                        border-radius: 8px;
                        font-size: 14px;
                        background: #ffffff;
                        transition: all 0.2s ease;
                    ">
                </div>
                <!-- Approved By -->
                <div>
                    <label for="modal_approved_by_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Approved By</label>
                    <input id="modal_approved_by_name" type="text" name="approved_by_name" placeholder="" value="{{ old('approved_by_name') }}" style="
                        width: 100%;
                        padding: 12px 16px;
                        border: 2px solid #e2e8f0;
                        border-radius: 8px;
                        font-size: 14px;
                        background: #ffffff;
                        transition: all 0.2s ease;
                    ">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
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

<!-- PDF Viewer Modal -->
<div id="pdfViewerModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.8); overflow: auto;">
    <div style="background-color: #fefefe; margin: 1% auto; padding: 0; border: 1px solid #888; border-radius: 8px; width: 95%; max-width: 1000px; height: 95%; max-height: 900px; display: flex; flex-direction: column;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid #ddd; background: #f8f9fa;">
            <h2 style="margin: 0; color: #1565c0; font-size: 18px;">Maintenance PDF Preview</h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <a id="pdfDownloadLink" href="#" target="_blank" style="background: #ff9b00; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none;">Download PDF</a>
                <span onclick="closePDFModal()" style="color: #666; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; background: #f1f3f4; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">&times;</span>
            </div>
        </div>
        <div id="pdfContent" style="flex: 1; overflow: auto; background: white;">
            <div style="padding: 20px; text-align: center; color: #64748b;">
                Loading PDF preview...
            </div>
        </div>
    </div>
</div>

<script>
    function openPDFModal(maintenanceId) {
        const modal = document.getElementById('pdfViewerModal');
        const pdfContent = document.getElementById('pdfContent');
        const downloadLink = document.getElementById('pdfDownloadLink');
        
        // Show loading message
        pdfContent.innerHTML = '<div style="padding: 40px; text-align: center; color: #64748b; font-size: 16px;">Loading PDF preview...</div>';
        
        // Set the download link
        downloadLink.href = "{{ route('maintenances.exportPDF', ':id') }}".replace(':id', maintenanceId);
        
        // Fetch the PDF HTML content
        fetch("{{ route('maintenances.viewPDF', ':id') }}".replace(':id', maintenanceId))
            .then(response => response.blob())
            .then(blob => {
                // Create object URL for the blob
                const url = URL.createObjectURL(blob);
                
                // Create an iframe to display the PDF
                pdfContent.innerHTML = '<iframe src="' + url + '" style="width: 100%; height: 100%; border: none;"></iframe>';
                
                // Clean up the object URL when modal is closed
                modal.dataset.blobUrl = url;
            })
            .catch(error => {
                console.error('Error loading PDF:', error);
                pdfContent.innerHTML = '<div style="padding: 40px; text-align: center; color: #dc2626; font-size: 16px;">Error loading PDF preview. Please try downloading the PDF instead.</div>';
            });
        
        // Show the modal
        modal.style.display = 'block';
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
    
    function closePDFModal() {
        const modal = document.getElementById('pdfViewerModal');
        const pdfContent = document.getElementById('pdfContent');
        
        // Clean up blob URL if it exists
        if (modal.dataset.blobUrl) {
            URL.revokeObjectURL(modal.dataset.blobUrl);
            delete modal.dataset.blobUrl;
        }
        
        // Hide the modal
        modal.style.display = 'none';
        
        // Clear the content
        pdfContent.innerHTML = '<div style="padding: 20px; text-align: center; color: #64748b;">Loading PDF preview...</div>';
        
        // Restore body scroll
        document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('pdfViewerModal');
        if (event.target === modal) {
            closePDFModal();
        }
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePDFModal();
        }
    });
</script>

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>

@endsection
