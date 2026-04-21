@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
    /* Fixed Header and Sidebar Layout */
    .dashboard-header {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1100 !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px);
        height: 70px;
        padding: 10px 20px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-body {
        margin-top: 70px; /* Offset for fixed header */
        display: flex;
        height: calc(100vh - 70px);
        overflow: hidden;
        padding: 0 !important;
        gap: 0 !important;
    }

    .dashboard-nav {
        position: fixed !important;
        top: 70px;
        left: 0;
        width: 240px;
        height: calc(100vh - 70px) !important;
        overflow-y: auto;
        z-index: 1000;
        border-radius: 0 !important;
        margin: 0 !important;
        border-right: 1px solid #e2e8f0;
        flex: none !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .dashboard-container {
        margin-left: 240px; /* Offset for fixed sidebar */
        display: flex !important;
        flex-direction: column !important;
        flex: 1;
        overflow-y: auto !important;
        height: calc(100vh - 70px);
        padding: 24px !important;
        border: none !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        scrollbar-width: thin;
    }

    /* Mobile overrides */
    @media (max-width: 768px) {
        .dashboard-nav {
            display: none !important;
        }
        .dashboard-container {
            margin-left: 0 !important;
            padding: 16px !important;
        }
    }
</style>

<div class="dashboard-page">
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>
            </div>

    <div class="dashboard-body">
        <nav class="dashboard-nav">
            @include('partials.sidebar-profile')
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M9 17v-2H4.5A2.5 2.5 0 012 12.5v-9A2.5 2.5 0 014.5 1h9A2.5 2.5 0 0116 3.5V9h-2V3.5a.5.5 0 00-.5-.5h-9a.5.5 0 00-.5.5v9a.5.5 0 00.5.5H9z"/><path d="M19 23h-9a2.5 2.5 0 01-2.5-2.5v-9a2.5 2.5 0 012.5-2.5h9a2.5 2.5 0 012.5 2.5v9a2.5 2.5 0 01-2.5 2.5zM10 11a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h9a.5.5 0 00.5-.5v-9a.5.5 0 00-.5-.5h-9z"/><circle cx="14.5" cy="17.5" r="1.5"/></svg>Reports</a>
                
                <div class="bottom-section">
                    <a href="{{ route('offices.index') }}" class="{{ request()->routeIs('offices.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                    <a href="{{ route('offices.manage-boardmembers') }}" class="{{ request()->routeIs('offices.manage-boardmembers') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('boardmember.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                <div class="bottom-section">
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                    </form>
                </div>
            @endif
        </nav>

        <div class="dashboard-container">
            <div class="page-header">
                <div>
                    <h2>Profile Settings</h2>
                    <p class="sub-text">Manage your account information</p>
                </div>
            </div>

            @if(session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <div class="profile-settings-grid">
                {{-- Profile Info Card --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--primary-blue);fill:none;stroke-width:2;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <h3>Account Information</h3>
                    </div>
                    <div class="settings-card-body">
                        <div class="profile-info-row">
                            <span class="profile-label">Name: {{ $user->name }}</span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-label">Email: {{ $user->email }}</span>
                        </div>
                        @if(auth()->user()->role === 'admin')
                        <div class="profile-info-row">
                            <span class="profile-label">Role: {{ $user->role }}</span>
                        </div>
                        @endif
                        @if($user->office)
                        <div class="profile-info-row">
                            <span class="profile-label">Office: {{ $user->office->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Change Name Card --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--primary-blue);fill:none;stroke-width:2;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <h3>Change Name</h3>
                    </div>
                    <div class="settings-card-body">
                        <form action="{{ route('profile.updateName') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">New Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn-primary" style="margin-top:12px;">Update Name</button>
                        </form>
                    </div>
                </div>

                {{-- Change Password Card --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <svg viewBox="0 0 24 24" style="width:20px;height:20px;stroke:var(--primary-blue);fill:none;stroke-width:2;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        <h3>Change Password</h3>
                    </div>
                    <div class="settings-card-body">
                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" id="password" name="password" required>
                                @error('password')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn-primary" style="margin-top:12px;">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        var dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('show');
    }
    document.addEventListener('click', function() {
        var dropdown = document.getElementById('profileDropdown');
        if (dropdown) dropdown.classList.remove('show');
    });
</script>
@endsection
