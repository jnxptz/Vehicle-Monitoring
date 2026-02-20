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

            <div style="overflow-x: auto; padding: 24px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                    <thead>
                        <tr style="background: #0b77d6; border-bottom: 2px solid #0b77d6;">
                            <th style="padding: 14px 16px; text-align: left; color: white; font-weight: 600;">Boardmember Name</th>
                            <th style="padding: 14px 16px; text-align: left; color: white; font-weight: 600;">Current Office</th>
                            <th style="padding: 14px 16px; text-align: left; color: white; font-weight: 600;">Assign Office</th>
                            <th style="padding: 14px 16px; text-align: center; color: white; font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boardmembers as $boardmember)
                            <tr style="border-bottom: 1px solid #e6eef8; background: #fff; transition: background 0.2s;">
                                <td style="padding: 14px 16px;">
                                    <strong style="color: #1a202c;">{{ $boardmember->name }}</strong><br>
                                    <small style="color: #999; font-size: 13px;">{{ $boardmember->email }}</small>
                                </td>
                                <td style="padding: 14px 16px;">
                                    @if($boardmember->office)
                                        <span style="background: #e7f3ff; color: #0056b3; padding: 6px 10px; border-radius: 4px; font-size: 13px; font-weight: 500;">
                                            {{ $boardmember->office->name }}
                                        </span>
                                    @else
                                        <span style="color: #999; font-style: italic;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px;">
                                    <form action="{{ route('offices.assign-boardmember', $boardmember->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                                        @csrf
                                        @method('PUT')
                                        <select name="office_id" required style="flex: 1; min-width: 160px; padding: 8px 12px; border: 1px solid #d0d7de; border-radius: 4px; font-size: 14px; background: white; cursor: pointer;">
                                            <option value="">-- Select Office --</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" {{ $boardmember->office_id === $office->id ? 'selected' : '' }}>
                                                    {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="padding: 8px 18px; background: #0b77d6; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; font-size: 14px; white-space: nowrap; transition: background 0.2s;" onmouseover="this.style.background='#0a5fa8'" onmouseout="this.style.background='#0b77d6'">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td style="padding: 14px 16px; text-align: center;">
                                    @if($boardmember->office)
                                        <span style="color: #22863a; font-weight: 600; font-size: 13px;">✓ Assigned</span>
                                    @else
                                        <span style="color: #cb2431; font-weight: 600; font-size: 13px;">✗ Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 32px 16px; text-align: center; color: #999;">
                                    <p style="font-size: 15px;">No boardmembers found in the system.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 12px;">
                <a href="{{ route('admin.dashboard') }}" style="padding: 10px 18px; background: #f3f4f6; color: #1a202c; border: 1px solid #d0d7de; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
