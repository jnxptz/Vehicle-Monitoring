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
            <div class="form-card">
                <h2>Create Office</h2>

                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('offices.store') }}" method="POST">
                    @csrf

                    <label for="name">Office Name:</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        required
                        value="{{ old('name') }}"
                        placeholder="e.g., Pangasinan Office"
                    >

                    <label for="address">Address (optional):</label>
                    <textarea
                        id="address"
                        name="address"
                        placeholder="Enter office address"
                    >{{ old('address') }}</textarea>

                    <button type="submit">Create Office</button>
                </form>

                <div style="margin-top: 20px;">
                    <a href="{{ route('offices.index') }}" class="btn-primary btn-sm" style="text-decoration: none;">← Back to Offices</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
