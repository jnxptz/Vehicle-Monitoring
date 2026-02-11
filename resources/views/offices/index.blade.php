@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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
            <a href="{{ route('offices.index') }}" class="active">Offices</a>
            <a href="{{ route('vehicles.index') }}">Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
            <a href="{{ route('offices.manage-boardmembers') }}">Manage Boardmembers</a>
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
                <h2>Offices</h2>
                <a href="{{ route('offices.create') }}" class="btn-primary btn-sm">+ Create Office</a>
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
                @if($offices->count() > 0)
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 12px; text-align: left;">Office Name</th>
                                <th style="padding: 12px; text-align: left;">Address</th>
                                <th style="padding: 12px; text-align: center;">Vehicles</th>
                                <th style="padding: 12px; text-align: center;">Boardmembers</th>
                                <th style="padding: 12px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offices as $office)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px;">
                                        <strong>{{ $office->name }}</strong>
                                    </td>
                                    <td style="padding: 12px; color: #666;">
                                        {{ $office->address ?? '—' }}
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: #e7f3ff; color: #0056b3; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                            {{ $office->vehicles_count }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                            {{ $office->users_count }}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="{{ route('offices.edit', $office->id) }}" style="color: #007bff; text-decoration: none; margin-right: 12px;">Edit</a>
                                        <form action="{{ route('offices.destroy', $office->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this office?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: #dc3545; text-decoration: none; background: none; border: none; cursor: pointer; padding: 0;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 20px; text-align: center; color: #666;">
                        No offices found. <a href="{{ route('offices.create') }}">Create one now</a>.
                    </p>
                @endif
            </div>

            <div style="margin-top: 20px;">
                <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="text-decoration: none; display: inline-block;">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection
