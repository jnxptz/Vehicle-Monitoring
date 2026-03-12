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
            <div class="page-header">
                <h2>Offices</h2>
                <button onclick="openOfficeModal()" class="btn-primary btn-sm" style="padding: 10px 20px; background: linear-gradient(135deg, #ff9b00 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(255, 155, 0, 0.2);" onmouseover="this.style.background='linear-gradient(135deg, #d97706 0%, #b45309 100%)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(255, 155, 0, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #ff9b00 0%, #d97706 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(255, 155, 0, 0.2)';">+ Create Office</button>
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



            <div class="offices-cards-container" style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                padding: 24px;
                margin-top: 20px;
            ">
                @if($offices->count() > 0)
                    <div class="offices-grid" style="
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                        gap: 20px;
                    ">
                        @foreach($offices as $office)
                            <div class="office-card-item" style="
                                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                                border: 1px solid #e2e8f0;
                                border-radius: 12px;
                                padding: 20px;
                                transition: all 0.3s ease;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                                cursor: pointer;
                            " onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 25px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.04)';" onclick="toggleOfficeDetails({{ $office->id }})">
                                <div style="
                                    background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);
                                    margin: -20px -20px 16px -20px;
                                    padding: 16px 20px;
                                    border-radius: 12px 12px 0 0;
                                ">
                                    <h3 style="
                                        margin: 0;
                                        color: #ffffff;
                                        font-size: 16px;
                                        font-weight: 600;
                                    ">{{ $office->name }}</h3>
                                </div>
                                 
                                <div style="
                                    display: flex;
                                    justify-content: space-around;
                                    margin: 16px 0;
                                    gap: 12px;
                                ">
                                    <div style="text-align: center; flex: 1;">
                                        <div style="
                                            background: #dbeafe;
                                            border-radius: 8px;
                                            padding: 12px;
                                        ">
                                            <div style="
                                                font-size: 24px;
                                                font-weight: 700;
                                                color: #1e40af;
                                                line-height: 1;
                                            ">{{ $office->vehicles_count }}</div>
                                            <div style="
                                                font-size: 11px;
                                                color: #64748b;
                                                margin-top: 4px;
                                                text-transform: uppercase;
                                                letter-spacing: 0.5px;
                                            ">Vehicles</div>
                                        </div>
                                    </div>
                                    <div style="text-align: center; flex: 1;">
                                        <div style="
                                            background: #fef3c7;
                                            border-radius: 8px;
                                            padding: 12px;
                                        ">
                                            <div style="
                                                font-size: 24px;
                                                font-weight: 700;
                                                color: #d97706;
                                                line-height: 1;
                                            ">{{ $office->users_count }}</div>
                                            <div style="
                                                font-size: 11px;
                                                color: #64748b;
                                                margin-top: 4px;
                                                text-transform: uppercase;
                                                letter-spacing: 0.5px;
                                            ">Users</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Expandable Details Section -->
                                <div id="office-details-{{ $office->id }}" style="display: none; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                                    <!-- Vehicles Section -->
                                    <div style="margin-bottom: 20px;">
                                        <h4 style="margin: 0 0 12px 0; color: #1e40af; font-size: 14px; font-weight: 600;">🚗 Vehicles ({{ $office->vehicles_count }})</h4>
                                        @if($office->vehicles && $office->vehicles->count() > 0)
                                            <div style="background: #f8fafc; border-radius: 8px; padding: 12px;">
                                                @foreach($office->vehicles as $vehicle)
                                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #e2e8f0; {{ $loop->last ? 'border-bottom: none' : '' }}">
                                                        <div>
                                                            <div style="font-weight: 500; color: #1e293b;">{{ $vehicle->plate_number }}</div>
                                                            <div style="color: #64748b; font-size: 13px; margin-top: 2px;">{{ $vehicle->vehicle_name }}</div>
                                                            <div style="color: #059669; font-size: 12px; margin-top: 2px;">
                                                                👤 {{ $vehicle->bm->name ?? 'No Owner' }}
                                                            </div>
                                                        </div>
                                                        <div style="text-align: right;">
                                                            <div style="font-size: 12px; color: #64748b;">Fuel Limit</div>
                                                            <div style="font-weight: 600; color: #059669;">{{ $vehicle->monthly_fuel_limit }}L</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div style="text-align: center; padding: 20px; color: #64748b; font-size: 14px;">
                                                No vehicles assigned to this office
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Users Section -->
                                    <div>
                                        <h4 style="margin: 0 0 12px 0; color: #d97706; font-size: 14px; font-weight: 600;">👥 Users ({{ $office->users_count }})</h4>
                                        @if($office->users && $office->users->count() > 0)
                                            <div style="background: #fffbeb; border-radius: 8px; padding: 12px;">
                                                @foreach($office->users as $user)
                                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #fde68a; {{ $loop->last ? 'border-bottom: none' : '' }}">
                                                        <div>
                                                            <span style="font-weight: 500; color: #1e293b;">{{ $user->name }}</span>
                                                            <span style="color: #64748b; margin-left: 8px; font-size: 12px;">{{ $user->email }}</span>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div style="text-align: center; padding: 20px; color: #64748b; font-size: 14px;">
                                                No users assigned to this office
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                 
                                <div style="
                                    display: flex;
                                    gap: 8px;
                                    margin-top: 16px;
                                    padding-top: 16px;
                                    border-top: 1px solid #e2e8f0;
                                ">
                                    <a href="javascript:void(0)" 
                                       onclick="openEditOfficeModal({{ $office->id }}, '{{ $office->name }}')" 
                                       style="
                                           flex: 1;
                                           background: #eff6ff;
                                           color: #1d4ed8;
                                           text-decoration: none;
                                           padding: 10px 16px;
                                           border-radius: 6px;
                                           font-size: 13px;
                                           font-weight: 500;
                                           text-align: center;
                                           transition: all 0.2s;
                                       "
                                       onmouseover="this.style.background='#dbeafe';"
                                       onmouseout="this.style.background='#eff6ff';">
                                        Edit
                                    </a>
                                    <form action="{{ route('offices.destroy', $office->id) }}" method="POST" style="flex: 1; margin: 0;" onsubmit="return confirm('Are you sure you want to delete this office?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="
                                            width: 100%;
                                            background: #fef2f2;
                                            color: #dc2626;
                                            border: none;
                                            padding: 10px 16px;
                                            border-radius: 6px;
                                            font-size: 13px;
                                            font-weight: 500;
                                            cursor: pointer;
                                            transition: all 0.2s;
                                        " onmouseover="this.style.background='#fee2e2';" onmouseout="this.style.background='#fef2f2';">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px;">
                        <div style="font-size: 48px; margin-bottom: 16px;">🏢</div>
                        <p style="color: #64748b; font-size: 16px; margin: 0;">No offices found. <a href="javascript:void(0)" onclick="openOfficeModal()" style="color: #3b82f6; text-decoration: none;">Create one now</a>.</p>
                    </div>
                @endif
            </div>




        </div>

    </div>

</div>



<script>

    function openOfficeModal() {

        document.getElementById('officeModal').style.display = 'block';

    }



    function closeOfficeModal() {

        document.getElementById('officeModal').style.display = 'none';

    }



    function openEditOfficeModal(id, name) {

        document.getElementById('editOfficeModal').style.display = 'block';

        document.getElementById('editOfficeName').value = name;

        document.getElementById('editForm').action = '{{ url('offices') }}/' + id;

    }



    function closeEditOfficeModal() {

        document.getElementById('editOfficeModal').style.display = 'none';

    }

    function toggleOfficeDetails(officeId) {
        const details = document.getElementById('office-details-' + officeId);
        if (details.style.display === 'none' || details.style.display === '') {
            details.style.display = 'block';
        } else {
            details.style.display = 'none';
        }
    }



    window.onclick = function(event) {

        const modal = document.getElementById('officeModal');

        if (event.target === modal) {

            closeOfficeModal();

        }

        const editModal = document.getElementById('editOfficeModal');

        if (event.target === editModal) {

            closeEditOfficeModal();

        }

    }

</script>



<!-- Create Office Modal -->

<div id="officeModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">

    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

            <h2 id="formTitle" style="margin:0; color:#1565c0;">Create Office</h2>

            <span onclick="closeOfficeModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>

        </div>

        <form id="officeForm" action="{{ route('offices.store') }}" method="POST">

            @csrf

            <label style="display:block; margin-bottom:12px; font-weight:600;">Office Name:</label>

            <input type="text" name="name" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <button id="submitBtn" type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Create Office</button>

        </form>

    </div>

</div>

<!-- Edit Office Modal -->

<div id="editOfficeModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">

    <div style="background-color:#fefefe; margin:10% auto; padding:30px; border:1px solid #888; border-radius:8px; width:90%; max-width:500px;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">

            <h2 style="margin:0; color:#1565c0;">Edit Office</h2>

            <span onclick="closeEditOfficeModal()" style="color:#aaa; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>

        </div>

        <form id="editForm" action="" method="POST">

            @csrf

            @method('PUT')

            <label style="display:block; margin-bottom:12px; font-weight:600;">Office Name:</label>

            <input id="editOfficeName" type="text" name="name" required style="width:100%; padding:8px; margin-bottom:20px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box;">

            <button type="submit" style="background:#007bff; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer; width:100%; font-weight:600;">Update Office</button>

        </form>

    </div>

</div>

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>

@endsection
