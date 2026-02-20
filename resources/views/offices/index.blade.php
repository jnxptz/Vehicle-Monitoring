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
            <a href="{{ route('offices.manage-boardmembers') }}">Manage Users</a>
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
                <button onclick="openOfficeModal()" class="btn-primary btn-sm">+ Create Office</button>
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
                    <table class="office-table">
                        <thead>
                            <tr>
                                <th>Office Name</th>
                                <th style="text-align:center;">Vehicles</th>
                                <th style="text-align:center;">Users</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offices as $office)
                                <tr>
                                    <td>
                                        <strong>{{ $office->name }}</strong>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="badge badge--info">{{ $office->vehicles_count }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <span class="badge badge--warn">{{ $office->users_count }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        <a href="javascript:void(0)" onclick="openEditOfficeModal({{ $office->id }}, '{{ $office->name }}', '{{ addslashes($office->address) }}')" class="link-edit">Edit</a>
                                        <form action="{{ route('offices.destroy', $office->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this office?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="link-delete">Delete</button>
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
                <a href="{{ route('admin.dashboard') }}" class="btn-primary">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<script>
    function openOfficeModal() {
        document.getElementById('officeModal').style.display = 'block';
        document.getElementById('officeForm').reset();
        document.getElementById('officeForm').action = '{{ route('offices.store') }}';
        document.getElementById('formTitle').innerText = 'Create Office';
        document.getElementById('submitBtn').innerText = 'Create Office';
        document.querySelector('input[name="_method"]')?.remove();
    }

    function openEditOfficeModal(id, name, address) {
        document.getElementById('officeModal').style.display = 'block';
        document.getElementById('name').value = name;
        document.getElementById('address').value = address;
        document.getElementById('officeForm').action = '/offices/' + id;
        document.getElementById('formTitle').innerText = 'Edit Office';
        document.getElementById('submitBtn').innerText = 'Update Office';
        
        // Add method override for PUT request
        let methodInput = document.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('officeForm').appendChild(methodInput);
        } else {
            methodInput.value = 'PUT';
        }
    }

    function closeOfficeModal() {
        document.getElementById('officeModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('officeModal');
        if (event.target === modal) {
            closeOfficeModal();
        }
    }
</script>

<!-- Office Modal -->
<div id="officeModal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 id="formTitle" style="margin:0;">Create Office</h2>
            <span onclick="closeOfficeModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
        </div>

        <form id="officeForm" action="{{ route('offices.store') }}" method="POST">
            @csrf

            <label for="name" style="display:block; margin-bottom:12px; font-weight:600;">Office Name:</label>
            <input id="name" type="text" name="name" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="e.g., Pangasinan Office">

            <label for="address" style="display:block; margin-bottom:12px; font-weight:600;">Address (optional):</label>
            <textarea id="address" name="address" style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;" placeholder="Enter office address"></textarea>

            <button id="submitBtn" type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Create Office</button>
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
