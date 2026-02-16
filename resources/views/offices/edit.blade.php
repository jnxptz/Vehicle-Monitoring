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
            <div style="margin-top: 20px;">
                <a href="{{ route('offices.index') }}" class="btn-primary">← Back to Offices</a>
            </div>
        </div>
    </div>
</div>

<script>
    function closeOfficeModal() {
        document.getElementById('officeModal').style.display = 'none';
        window.location.href = '{{ route('offices.index') }}';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('officeModal');
        if (event.target === modal) {
            closeOfficeModal();
        }
    }

    // Open modal on page load
    window.addEventListener('load', function() {
        document.getElementById('officeModal').style.display = 'block';
    });
</script>

<!-- Edit Office Modal -->
<div id="officeModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="margin:0;">Edit Office</h2>
            <span onclick="closeOfficeModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form action="{{ route('offices.update', $office->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name" style="display:block; margin-bottom:12px; font-weight:600;">Office Name:</label>
            <input id="name" type="text" name="name" required value="{{ old('name', $office->name) }}" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="e.g., Pangasinan Office">

            <label for="address" style="display:block; margin-bottom:12px; font-weight:600;">Address (optional):</label>
            <textarea id="address" name="address" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="Enter office address">{{ old('address', $office->address) }}</textarea>

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Update Office</button>
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
