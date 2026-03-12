@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

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
                <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                <div class="bottom-section">
                    <a href="{{ route('offices.index') }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                    <a href="{{ route('offices.manage-boardmembers') }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                    </form>
                </div>
            @else
                @include('partials.sidebar-profile')
                
                <a href="{{ route('boardmember.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
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
                            <span class="profile-label">Name</span>
                            <span class="profile-value">{{ $user->name }}</span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-label">Email</span>
                            <span class="profile-value">{{ $user->email }}</span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-label">Role</span>
                            <span class="profile-value" style="text-transform:capitalize;">{{ $user->role }}</span>
                        </div>
                        @if($user->office)
                        <div class="profile-info-row">
                            <span class="profile-label">Office</span>
                            <span class="profile-value">{{ $user->office->name }}</span>
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

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>

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
