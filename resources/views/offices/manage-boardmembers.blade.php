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
            <a href="{{ route('offices.manage-boardmembers') }}" class="active">Manage Boardmembers</a>
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
                <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="form-card" style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 12px; text-align: left;">Boardmember Name</th>
                            <th style="padding: 12px; text-align: left;">Current Office</th>
                            <th style="padding: 12px; text-align: left;">Assign Office</th>
                            <th style="padding: 12px; text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($boardmembers as $boardmember)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 12px;">
                                    <strong>{{ $boardmember->name }}</strong><br>
                                    <small style="color: #666;">{{ $boardmember->email }}</small>
                                </td>
                                <td style="padding: 12px;">
                                    @if($boardmember->office)
                                        <span style="background: #e7f3ff; color: #0056b3; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                            {{ $boardmember->office->name }}
                                        </span>
                                    @else
                                        <span style="color: #dc3545; font-weight: bold;">Not Assigned</span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <form action="{{ route('offices.assign-boardmember', $boardmember->id) }}" method="POST" style="display: flex; gap: 8px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="office_id" required style="flex: 1; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px;">
                                            <option value="">-- Select Office --</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" {{ $boardmember->office_id === $office->id ? 'selected' : '' }}>
                                                    {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" style="padding: 6px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($boardmember->office)
                                        <span style="color: #28a745;">✓ Assigned</span>
                                    @else
                                        <span style="color: #dc3545;">✗ No Office</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 20px; text-align: center; color: #666;">
                                    No boardmembers found in the system.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="text-decoration: none; display: inline-block;">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
