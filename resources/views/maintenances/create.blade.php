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
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
                <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="active"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                
                <div class="bottom-section">
                    <a href="{{ route('offices.index') }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                    <a href="{{ route('offices.manage-boardmembers') }}"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                    @include('partials.sidebar-profile')
                    
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('boardmember.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
                <a href="{{ route('maintenances.index') }}" class="active"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
                
                <div class="bottom-section">
                    @include('partials.sidebar-profile')
                    
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                    </form>
                </div>
            @endif
        </nav>

        <div class="dashboard-container">
            <div class="form-layout">
                <div class="page-header">
                    <div>
                        <h2>Add Maintenance Record</h2>
                        <p class="sub-text">Schedule and track vehicle maintenance</p>
                    </div>
                    <a href="{{ route('maintenances.index') }}" class="btn-primary btn-sm">← Back to Maintenances</a>
                </div>

                @if ($errors->any())
                    <div class="error-message" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                        <div style="font-weight: 600; margin-bottom: 8px;">⚠️ Please fix the following errors:</div>
                        @foreach ($errors->all() as $error)
                            <div style="margin-bottom: 4px;">• {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="form-block" style="
                    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    padding: 32px;
                    border: 1px solid #e2e8f0;
                ">
                    <form action="{{ route('maintenances.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Vehicle Selection -->
                            <div>
                                <label for="vehicle_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">🚗 Select Vehicle</label>
                                <div style="position: relative;">
                                    <select id="vehicle_id" name="vehicle_id" required style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        background: #ffffff;
                                        font-size: 14px;
                                        color: #1e293b;
                                        appearance: none;
                                        background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http://www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%3E%3Cpath%20d%3D%22M7%2010l5%205%205%205z%22%20fill%3D%22%236b7280%22/%3E%3C/svg%3E');
                                        background-repeat: no-repeat;
                                        background-position: right 12px center;
                                        background-size: 20px;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                        <option value="">Choose a vehicle...</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->plate_number }} - {{ $vehicle->vehicle_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Maintenance Type -->
                            <div>
                                <label for="maintenance_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">🔧 Maintenance Type</label>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff';">
                                        <input type="radio" name="maintenance_type" value="preventive" {{ old('maintenance_type', 'preventive') === 'preventive' ? 'checked' : '' }} style="margin-right: 8px;">
                                        <span style="font-weight: 500;">🛡️ Preventive</span>
                                    </label>
                                    <label style="display: flex; align-items: center; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#ffffff';">
                                        <input type="radio" name="maintenance_type" value="repair" {{ old('maintenance_type') === 'repair' ? 'checked' : '' }} style="margin-right: 8px;">
                                        <span style="font-weight: 500;">🔨 Repair</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Odometer Reading -->
                            <div>
                                <label for="maintenance_km" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">📊 Odometer Reading</label>
                                <div style="position: relative;">
                                    <input id="maintenance_km" type="number" name="maintenance_km" min="0" required value="{{ old('maintenance_km') }}" placeholder="15000" style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        background: #ffffff;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                    <div style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 14px;">
                                        KM
                                    </div>
                                </div>
                            </div>

                            <!-- Cost -->
                            <div>
                                <label for="cost" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">💰 Cost</label>
                                <div style="position: relative;">
                                    <input id="cost" type="number" step="0.01" name="cost" required value="{{ old('cost') }}" placeholder="0.00" style="
                                        width: 100%;
                                        padding: 12px 16px 12px 16px 40px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        background: #ffffff;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                    <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 16px;">
                                        ₱
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Operations Done -->
                        <div style="margin-bottom: 24px;">
                            <label for="operation" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">🔧 Operations Performed</label>
                            <textarea id="operation" name="operation" rows="4" required placeholder="Describe the maintenance operations performed..." style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e2e8f0;
                                border-radius: 8px;
                                font-size: 14px;
                                font-family: inherit;
                                resize: vertical;
                                background: #ffffff;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">{{ old('operation') }}</textarea>
                        </div>

                        <!-- Conduct Details -->
                        <div style="margin-bottom: 24px;">
                            <label for="conduct" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">👨‍🔧 Conducted By</label>
                            <input id="conduct" type="text" name="conduct" required value="{{ old('conduct') }}" placeholder="Mechanic name or service center" style="
                                width: 100%;
                                padding: 12px 16px;
                                border: 2px solid #e2e8f0;
                                border-radius: 8px;
                                font-size: 14px;
                                background: #ffffff;
                                transition: all 0.2s ease;
                            " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Date -->
                            <div>
                                <label for="date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">📅 Date</label>
                                <input id="date" type="date" name="date" required value="{{ old('date') ?? now()->format('Y-m-d') }}" style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 8px;
                                    font-size: 14px;
                                    background: #ffffff;
                                    transition: all 0.2s ease;
                                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <!-- Prepared By -->
                            <div>
                                <label for="prepared_by_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Prepared By</label>
                                <input id="prepared_by_name" type="text" name="prepared_by_name" placeholder="" value="{{ old('prepared_by_name') }}" style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 8px;
                                    font-size: 14px;
                                    background: #ffffff;
                                    transition: all 0.2s ease;
                                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                            <!-- Approved By -->
                            <div>
                                <label for="approved_by_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Approved By</label>
                                <input id="approved_by_name" type="text" name="approved_by_name" placeholder="" value="{{ old('approved_by_name') }}" style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 8px;
                                    font-size: 14px;
                                    background: #ffffff;
                                    transition: all 0.2s ease;
                                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <!-- Photo Upload -->
                            <div>
                                <label for="photo" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">📷 Photo (Optional)</label>
                                <div style="
                                    border: 2px dashed #cbd5e1;
                                    border-radius: 8px;
                                    padding: 24px;
                                    text-align: center;
                                    background: #f8fafc;
                                    transition: all 0.2s ease;
                                " onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';">
                                    <input id="photo" type="file" name="photo" accept="image/*" style="display: none;" onchange="document.getElementById('photo-name').textContent = this.files[0]?.name || 'No file selected'">
                                    <div style="margin-bottom: 8px;">
                                        <div style="font-size: 32px; color: #64748b;">📷</div>
                                        <div id="photo-name" style="color: #1e293b; font-weight: 500;">Choose a photo or drag & drop</div>
                                    </div>
                                    <div style="font-size: 12px; color: #64748b; margin-top: 8px;">
                                        Supported formats: JPG, PNG, GIF (Max 5MB)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="color: #64748b; font-size: 14px;">
                                <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                    <span style="color: #059669; margin-right: 4px;">ℹ️</span>
                                    Call of No. will be automatically generated
                                </div>
                                <div style="font-size: 12px;">
                                    Format: MN-YYYYMMDD-XXXXXX
                                </div>
                            </div>
                            <button type="submit" class="btn-primary" style="
                                padding: 14px 32px;
                                font-size: 16px;
                                font-weight: 600;
                                background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: all 0.2s ease;
                                box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
                            " onmouseover="this.style.background='linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(139, 92, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(139, 92, 246, 0.2)';">
                                🚀 Create Maintenance Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="dashboard-footer">
    &copy; {{ date('Y') }} <span>Vehicle Monitoring System</span> <span class="footer-divider">|</span> Sangguniang Panlalawigan - Provincial Government of La Union
</footer>
@endsection

