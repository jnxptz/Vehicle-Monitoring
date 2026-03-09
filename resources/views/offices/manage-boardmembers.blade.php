@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

<div class="dashboard-page">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Sangguniang Panlalawigan</h1>
        </div>
    </div>

    <div class="dashboard-body">
        {{-- Sidebar --}}
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
            <a href="{{ route('offices.index') }}">Offices</a>
            <a href="{{ route('offices.manage-boardmembers') }}" class="active">Manage Users</a>
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
                            <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
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
                                            <button type="submit" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; white-space: nowrap; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle; text-align: center;">
                                        @if($boardmember->office)
                                            <span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #dcfce7; color: #166534;">✓ Assigned</span>
                                        @else
                                            <span style="padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #fee2e2; color: #dc2626;">✗ Pending</span>
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
@endsection
