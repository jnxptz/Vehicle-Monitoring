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
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('boardmember.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('fuel-slips.index') }}" class="{{ request()->routeIs('fuel-slips.*') ? 'active' : '' }}">Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}" class="{{ request()->routeIs('maintenances.*') ? 'active' : '' }}">Maintenances</a>
            <div style="margin-top: auto; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </nav>

        <div class="dashboard-container">
            <div class="form-layout">
                <div class="page-header">
                    <div>
                        <h2>Add Fuel Slip</h2>
                        <p class="sub-text">Record fuel consumption and expenses</p>
                    </div>
                    <a href="{{ route('fuel-slips.index') }}" class="btn-primary btn-sm">← Back to Fuel Slips</a>
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
                    <form action="{{ route('fuel-slips.store') }}" method="POST">
                        @csrf
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Boardmember Selection -->
                            <div>
                                <label for="boardmember_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Select Boardmember</label>
                                <div style="position: relative;">
                                    <select id="boardmember_id" name="boardmember_id" style="
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
                                        <option value="">Choose a boardmember...</option>
                                        @foreach($boardmembers as $bm)
                                            <option value="{{ $bm->id }}">{{ $bm->name }} ({{ $bm->office->name ?? 'No Office' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Vehicle Selection -->
                            <div>
                                <label for="vehicle_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Select Vehicle</label>
                                <div style="position: relative;">
                                    <select id="vehicle_id" name="vehicle_id" style="
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
                                        @foreach($boardmembers as $bm)
                                            @foreach($bm->office?->vehicles ?? [] as $v)
                                                <option value="{{ $v->id }}" data-name="{{ $v->vehicle_name }}" data-plate="{{ $v->plate_number }}" data-driver="{{ $v->driver }}" data-km="{{ $v->current_km ?? 0 }}" data-boardmember="{{ $bm->id }}">{{ $v->plate_number }} — {{ $v->vehicle_name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Vehicle Name -->
                            <div>
                                <label for="vehicle_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Vehicle Name</label>
                                <input id="vehicle_name" type="text" name="vehicle_name" placeholder="" value="{{ old('vehicle_name') }}" style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 8px;
                                    font-size: 14px;
                                    background: #ffffff;
                                    transition: all 0.2s ease;
                                " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                            </div>

                            <!-- Plate Number -->
                            <div>
                                <label for="plate_number" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Plate Number</label>
                                <input id="plate_number" type="text" name="plate_number" placeholder="" value="{{ old('plate_number') }}" style="
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

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Liters -->
                            <div>
                                <label for="liters" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Liters</label>
                                <div style="position: relative;">
                                    <input id="liters" type="number" step="0.01" name="liters" required value="{{ old('liters') }}" style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        background: #ffffff;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                </div>
                            </div>

                            <!-- Unit Cost -->
                            <div>
                                <label for="unit_cost" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Unit Cost (₱/L)</label>
                                <div style="position: relative;">
                                    <input id="unit_cost" type="number" step="0.01" name="unit_cost" required value="{{ old('unit_cost') }}" style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        background: #ffffff;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                </div>
                            </div>
                        </div>

                        <!-- Total Cost (Auto-calculated) -->
                        <div style="margin-bottom: 24px;">
                            <label for="total_cost" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Total Cost</label>
                            <div style="position: relative;">
                                <input id="total_cost" type="number" step="0.01" name="total_cost" readonly value="{{ old('total_cost') }}" style="
                                    width: 100%;
                                    padding: 12px 16px;
                                    border: 2px solid #e2e8f0;
                                    border-radius: 8px;
                                    font-size: 18px;
                                    font-weight: 600;
                                    background: #f8fafc;
                                    color: #059669;
                                    transition: all 0.2s ease;
                                "
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- KM Reading -->
                            <div>
                                <label for="km_reading" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">KM Reading</label>
                                <div style="position: relative;">
                                    <input id="km_reading" type="number" name="km_reading" required value="{{ old('km_reading') }}" style="
                                        width: 100%;
                                        padding: 12px 16px;
                                        border: 2px solid #e2e8f0;
                                        border-radius: 8px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        background: #ffffff;
                                        transition: all 0.2s ease;
                                    " onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                </div>
                            </div>

                            <!-- Driver -->
                            <div>
                                <label for="driver" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Driver</label>
                                <input id="driver" type="text" name="driver" required value="{{ old('driver') }}" placeholder="" style="
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

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <!-- Date -->
                            <div>
                                <label for="date" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Date</label>
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

                        <!-- Submit Button -->
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="color: #64748b; font-size: 14px;">
                                <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                    <span style="color: #059669; margin-right: 4px;">💡</span>
                                    Select a boardmember first to filter their vehicles, then choose a registered vehicle to auto-fill details.
                                </div>
                                <div style="font-size: 12px;">
                                    Vehicle name, plate number, driver, and KM reading will be auto-filled.
                                </div>
                            </div>
                            <button type="submit" class="btn-primary" style="
                                padding: 14px 32px;
                                font-size: 16px;
                                font-weight: 600;
                                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: all 0.2s ease;
                                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
                            " onmouseover="this.style.background='linear-gradient(135deg, #2563eb 0%, #1e40af 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(59, 130, 246, 0.3)';" onmouseout="this.style.background='linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(59, 130, 246, 0.2)';">
                                Create Fuel Slip
                            </button>
                        </div>
                    </form>
                                vehicleSelect.disabled = !boardmemberId;
                                vehicleSelect.value = '';
                                nameInput.value = '';
                                plateInput.value = '';
                                driverInput.value = '';
                                kmInput.value = '';
                            }

                            boardmemberSelect && boardmemberSelect.addEventListener('change', function(){
                                filterVehiclesByBoardmember(this.value);
                            });

                            vehicleSelect && vehicleSelect.addEventListener('change', function(){
                                const opt = this.options[this.selectedIndex];
                                const name = opt.getAttribute('data-name') || '';
                                const plate = opt.getAttribute('data-plate') || '';
                                const driver = opt.getAttribute('data-driver') || '';
                                const km = opt.getAttribute('data-km') || '';

                                if(name) nameInput.value = name;
                                if(plate) plateInput.value = plate;
                                if(driver) driverInput.value = driver;
                                if(km) kmInput.value = km;
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
