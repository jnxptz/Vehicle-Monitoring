@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

<div class="dashboard-page">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>
            </div>

    <div class="dashboard-body">
        {{-- Sidebar --}}
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            
            <div class="bottom-section">
                <a href="{{ route('offices.index') }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="active"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                @include('partials.sidebar-profile')
                
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <h2>Assign Offices to Boardmembers</h2>
                <p class="page-description">Select an office for each boardmember to enable vehicle registration and fuel slip creation.</p>
            </div>

            @if (session('success'))
                <div class="success-message">
                    <strong>✓ Success:</strong> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <strong>⚠ Error:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-container">
                <div class="table-wrapper">
                    <table style="width: 100%; border-collapse: collapse; font-size: 15px; border: none; table-layout: fixed;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 35%;">Boardmember Name</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 25%;">Current Office</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 30%;">Assign Office</th>
                                <th style="padding: 16px 20px; text-align: center; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 10%;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boardmembers as $boardmember)
                                <tr style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle;">
                                        <strong style="color: #1e293b; font-weight: 600; font-size: 13px; display: block; margin-bottom: 0; line-height: 1.1;">{{ $boardmember->name }}</strong><br>
                                        <small style="color: #64748b; font-size: 11px; display: block; word-break: break-all; line-height: 1.1; margin-top: 1px;">{{ $boardmember->email }}</small>
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle;">
                                        @if($boardmember->office)
                                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $boardmember->office->name }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8; font-style: italic; font-size: 13px;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle;">
                                        <form action="{{ route('offices.assign-boardmember', $boardmember->id) }}" method="POST" style="display: flex; gap: 8px; align-items: center; flex-wrap: nowrap;">
                                            @csrf
                                            @method('PUT')
                                            <select name="office_id" required style="flex: 1; min-width: 120px; max-width: 180px; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                                <option value="">-- Select Office --</option>
                                                @foreach($offices as $office)
                                                    <option value="{{ $office->id }}" {{ $boardmember->office_id === $office->id ? 'selected' : '' }}>
                                                        {{ $office->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #3b82f6 0%, #ff9b00 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; white-space: nowrap; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2); min-width: 80px;">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle; text-align: center;">
                                        @if($boardmember->office)
                                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #166534; white-space: nowrap;">✓ Assigned</span>
                                        @else
                                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #dc2626; white-space: nowrap;">✗ Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <p>No boardmembers found in the system.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            
        </div>
    </div>
</div>

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>
@endsection
