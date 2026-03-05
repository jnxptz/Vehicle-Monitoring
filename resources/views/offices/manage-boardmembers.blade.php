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
            <a href="{{ route('offices.index') }}">Offices</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
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
                <p style="color: #666; font-size: 14px; margin-top: 8px;">Select an office for each boardmember to enable vehicle registration and fuel slip creation.</p>
            </div>

            @if (session('success'))
                <div style="background: #d1e7dd; border: 1px solid #badbcc; color: #0f5132; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                    <strong>✓ Success:</strong> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #f8d7da; border: 1px solid #f5c2c7; color: #842029; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
                    <strong>⚠ Error:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="overflow-x: auto;">
                <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 15px; border: none;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Boardmember Name</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Current Office</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Assign Office</th>
                                <th style="padding: 16px 20px; text-align: center; color: #ffffff; font-weight: 600; font-size: 14px; border: none;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boardmembers as $boardmember)
                                <tr style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                    <td style="padding: 16px 20px; border: none;">
                                        <strong style="color: #1e293b;">{{ $boardmember->name }}</strong><br>
                                        <small style="color: #64748b; font-size: 13px;">{{ $boardmember->email }}</small>
                                    </td>
                                    <td style="padding: 16px 20px; border: none;">
                                        @if($boardmember->office)
                                            <span style="background: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 500;">
                                                {{ $boardmember->office->name }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8; font-style: italic;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; border: none;">
                                        <form action="{{ route('offices.assign-boardmember', $boardmember->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                                            @csrf
                                            @method('PUT')
                                            <select name="office_id" required style="flex: 1; min-width: 160px; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: border-color 0.2s;">
                                                <option value="">-- Select Office --</option>
                                                @foreach($offices as $office)
                                                    <option value="{{ $office->id }}" {{ $boardmember->office_id === $office->id ? 'selected' : '' }}>
                                                        {{ $office->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; white-space: nowrap; transition: all 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                    <td style="padding: 16px 20px; text-align: center; border: none;">
                                        @if($boardmember->office)
                                            <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">✓ Assigned</span>
                                        @else
                                            <span style="background: #fee2e2; color: #dc2626; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;">✗ Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="padding: 32px 20px; text-align: center; color: #64748b; border: none;">
                                        <p style="font-size: 15px;">No boardmembers found in the system.</p>
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
