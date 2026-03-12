@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

<div class="dashboard-page">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>
        @include('partials.user-profile-dropdown')
    </div>

    <div class="dashboard-body">
        {{-- Sidebar --}}
        <nav class="dashboard-nav">
            <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            
            <div class="bottom-section">
                <a href="{{ route('offices.index') }}" class="active"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
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

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>
@endsection
