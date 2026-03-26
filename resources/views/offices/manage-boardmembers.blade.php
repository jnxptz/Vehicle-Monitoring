@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">

<div class="dashboard-page">
    {{-- Header --}}
    <div class="dashboard-header">
        <div class="dashboard-title">
            <img src="{{ asset('images/SP Seal.png') }}" alt="Logo">
            <h1>Vehicle Monitoring System</h1>
        </div>
            </div>

    <div class="dashboard-body">
        {{-- Sidebar --}}
        <nav class="dashboard-nav">
            @include('partials.sidebar-profile')
            
            <a href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a>
            
            <a href="{{ route('vehicles.index') }}"><svg viewBox="0 0 24 24"><path d="M5 17h14M5 17a2 2 0 01-2-2V7a2 2 0 012-2h2.5l1.5-2h6l1.5 2H19a2 2 0 012 2v8a2 2 0 01-2 2M5 17v2m14-2v2"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg>Vehicles</a>
            <a href="{{ route('fuel-slips.index') }}"><svg viewBox="0 0 24 24"><path d="M3 22V5a2 2 0 012-2h6a2 2 0 012 2v17"/><path d="M13 10h4l2 2v10"/><path d="M7 11v2"/><path d="M17 14v2"/></svg>Fuel Slips</a>
            <a href="{{ route('maintenances.index') }}"><svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>Maintenances</a>
            
            <div class="bottom-section">
                <a href="{{ route('offices.index') }}"><svg viewBox="0 0 24 24"><path d="M3 21h18M9 8h1M9 12h1M9 16h1M14 8h1M14 12h1M14 16h1"/><path d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/></svg>Offices</a>
                <a href="{{ route('offices.manage-boardmembers') }}" class="active"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>Manage Users</a>
                
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn"><svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2;vertical-align:middle;margin-right:6px;"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>Logout</button>
                </form>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="dashboard-container">
            <div class="page-header">
                <h2>Assign Offices to Boardmembers</h2>
                <p class="page-description">Select an office for each boardmember to enable vehicle registration and fuel slip creation.</p>
            </div>

            @if (session('success'))
                <div class="success-message">
                    <strong>✓ Success:</strong> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <strong>⚠ Error:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-container">
                <div class="table-wrapper">
                    <table style="width: 100%; border-collapse: collapse; font-size: 15px; border: none; table-layout: fixed;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #1e40af 0%, #ff9b00 100%);">
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 35%;">Boardmember Name</th>
                                <th style="padding: 16px 20px; text-align: left; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 25%;">Current Office</th>
                                <th style="padding: 16px 20px; text-align: center; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 10%;">Status</th>
                                <th style="padding: 16px 20px; text-align: center; color: #ffffff; font-weight: 600; font-size: 14px; border: none; width: 30%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($boardmembers as $boardmember)
                                <tr style="background: {{ $loop->even ? '#f8fafc' : '#ffffff' }}; border-bottom: 1px solid #e2e8f0; transition: all 0.2s ease;" onmouseover="this.style.background='#eff6ff';" onmouseout="this.style.background='{{ $loop->even ? '#f8fafc' : '#ffffff' }}';">
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle;">
                                        <strong style="color: #1e293b; font-weight: 600; font-size: 13px; display: block; margin-bottom: 0; line-height: 1.1;">{{ $boardmember->name }}</strong><br>
                                        <small style="color: #64748b; font-size: 11px; display: block; word-break: break-all; line-height: 1.1; margin-top: 1px;">{{ $boardmember->email }}</small>
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle;">
                                        @if($boardmember->office)
                                            <span style="background: #dbeafe; color: #1d4ed8; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $boardmember->office->name }}
                                            </span>
                                        @else
                                            <span style="color: #94a3b8; font-style: italic; font-size: 13px;">—</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle; text-align: center;">
                                        @if($boardmember->office)
                                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #166534; white-space: nowrap;">✓ Assigned</span>
                                        @else
                                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #dc2626; white-space: nowrap;">✗ Pending</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; border: none; border-bottom: 1px solid #e2e8f0; color: #64748b; vertical-align: middle; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                            <button type="button" onclick="openEditModal({{ $boardmember->id }}, '{{ $boardmember->name }}', '{{ $boardmember->email }}', {{ $boardmember->office_id ?? 'null' }}, {{ $boardmember->bm ? $boardmember->bm->yearly_budget : 'null' }})" style="padding: 8px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.background='#2563eb';" onmouseout="this.style.background='#3b82f6';">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('boardmembers.destroy', $boardmember->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete {{ $boardmember->name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 8px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.background='#dc2626';" onmouseout="this.style.background='#ef4444';">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                                        <line x1="10" y1="11" x2="10" y2="17"/>
                                                        <line x1="14" y1="11" x2="14" y2="17"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <p>No boardmembers found in the system.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
                <div style="background-color: white; padding: 24px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="margin: 0; color: #1e293b; font-size: 18px; font-weight: 600;">Edit Boardmember</h3>
                        <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
                    </div>
                    
                    <form action="/boardmembers/" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div style="margin-bottom: 16px;">
                            <label for="edit_name" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 14px;">Name</label>
                            <input type="text" id="edit_name" name="name" required style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        </div>
                        
                        <div style="margin-bottom: 16px;">
                            <label for="edit_email" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 14px;">Email</label>
                            <input type="email" id="edit_email" name="email" required style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        </div>
                        
                        <div style="margin-bottom: 16px;">
                            <label for="edit_office_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 14px;">Office Assignment</label>
                            <select id="edit_office_id" name="office_id" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                                <option value="">-- No Office --</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <label for="edit_yearly_budget" style="display: block; margin-bottom: 6px; font-weight: 600; color: #374151; font-size: 14px;">Yearly Budget</label>
                            <input type="number" id="edit_yearly_budget" name="yearly_budget" step="0.01" min="0" placeholder="Enter yearly budget (optional)" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                        </div>
                        
                        <div style="display: flex; gap: 12px; justify-content: flex-end;">
                            <button type="button" onclick="closeEditModal()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">Cancel</button>
                            <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #3b82f6 0%, #ff9b00 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500;">Update Boardmember</button>
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

<script>
function openEditModal(userId, name, email, officeId, yearlyBudget) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_office_id').value = officeId || '';
    document.getElementById('edit_yearly_budget').value = yearlyBudget || '';
    
    // Update form action with user ID
    document.getElementById('editForm').action = '/boardmembers/' + userId;
    
    // Show modal
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target == modal) {
        closeEditModal();
    }
}
</script>

@endsection
