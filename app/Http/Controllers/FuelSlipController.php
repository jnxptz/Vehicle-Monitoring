<?php


namespace App\Http\Controllers;

use App\Models\FuelSlip;
use App\Models\Vehicle;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\MaintenanceDueMail;

class FuelSlipController extends Controller
{
    
    private function generateUniqueControlNumber(): string
    {
        $datePart = now()->format('Ymd');

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $randomPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $candidate = "FS-{$datePart}-{$randomPart}";

            if (!FuelSlip::where('control_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        
        return 'FS-' . now()->format('YmdHisv') . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Get offices for filter dropdown
            $offices = \App\Models\Office::orderBy('name')->get();
            
            // Apply office filter if selected
            $query = \App\Models\User::where('role', 'boardmember')
                ->whereNotNull('office_id')
                ->with(['office', 'fuelSlips' => function($q){ $q->latest(); }])
                ->orderBy('name');
                
            if (request('office')) {
                $query->where('office_id', request('office'));
            }
            
            $boardmembers = $query->get();
            $fuelSlips = FuelSlip::latest()->get(); // Keep for backward compatibility
            $maintenanceAlerts = []; // No alerts for admin
        } else {
            // For boardmember: fetch only their fuel slips and check maintenance alerts
            $fuelSlips = FuelSlip::where('user_id', $user->id)->latest()->get();
            $boardmembers = collect(); // Empty collection for boardmember view
            $offices = collect();
            
            // Check for maintenance alerts for boardmember's vehicles
            $maintenanceAlerts = [];
            $vehicles = $user->vehicles()->get();
            foreach ($vehicles as $vehicle) {
                // Get current KM from vehicle or latest fuel slip
                $currentKm = $vehicle->current_km ?? 0;
                $latestFuelSlip = FuelSlip::where('vehicle_id', $vehicle->id)
                    ->orderBy('km_reading', 'desc')
                    ->first();
                if ($latestFuelSlip && $latestFuelSlip->km_reading > $currentKm) {
                    $currentKm = $latestFuelSlip->km_reading;
                }

                // Get last maintenance
                $lastMaintenance = \App\Models\Maintenance::where('vehicle_id', $vehicle->id)
                    ->orderBy('date', 'desc')
                    ->first();
                
                $lastMaintenanceKm = $lastMaintenance ? $lastMaintenance->maintenance_km : 0;
                if ($lastMaintenanceKm == 0) {
                    $maxKm = \App\Models\Maintenance::where('vehicle_id', $vehicle->id)
                        ->whereNotNull('maintenance_km')
                        ->max('maintenance_km');
                    $lastMaintenanceKm = $maxKm ? (int) $maxKm : 0;
                }

                $lastMaintenanceType = $lastMaintenance ? $lastMaintenance->maintenance_type : 'N/A';
                $nextDueKm = ($lastMaintenanceKm > 0) ? ($lastMaintenanceKm + 5000) : 5000;
                
                if ($currentKm >= $nextDueKm) {
                    $maintenanceAlerts[] = "Vehicle {$vehicle->plate_number} ({$vehicle->vehicle_name}) is due for maintenance! Current: {$currentKm} km, Last maintenance ({$lastMaintenanceType}) at {$lastMaintenanceKm} km.";
                    
                    // Send email notification for maintenance due
                    $emailKey = 'maintenance_due_email_sent_' . $vehicle->id . '_' . date('Y-m-d');
                    if (!session()->has($emailKey) && $user->email) {
                        try {
                            Mail::to($user->email)->send(new MaintenanceDueMail($vehicle, $currentKm, $lastMaintenanceKm, $nextDueKm, $lastMaintenanceType));
                            \Log::info('Maintenance due email sent to: ' . $user->email . ' for vehicle: ' . $vehicle->plate_number);
                            session()->put($emailKey, true);
                        } catch (\Exception $e) {
                            \Log::error('Failed to send maintenance due email: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        return view('fuel_slips.index', compact('fuelSlips', 'boardmembers', 'offices', 'maintenanceAlerts'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Provide boardmembers with offices so admin can pick a boardmember then a vehicle
        $boardmembers = \App\Models\User::where('role', 'boardmember')
            ->whereNotNull('office_id')
            ->with(['office', 'vehicles' => function($q){ $q->orderBy('plate_number'); }])
            ->orderBy('name')
            ->get();

        // Check for maintenance alerts for all vehicles
        $maintenanceAlerts = [];
        foreach ($boardmembers as $bm) {
            foreach ($bm->office->vehicles ?? [] as $vehicle) {
                // Get current KM from vehicle or latest fuel slip
                $currentKm = $vehicle->current_km ?? 0;
                $latestFuelSlip = \App\Models\FuelSlip::where('vehicle_id', $vehicle->id)
                    ->orderBy('km_reading', 'desc')
                    ->first();
                if ($latestFuelSlip && $latestFuelSlip->km_reading > $currentKm) {
                    $currentKm = $latestFuelSlip->km_reading;
                }

                // Get last maintenance
                $lastMaintenance = \App\Models\Maintenance::where('vehicle_id', $vehicle->id)
                    ->orderBy('date', 'desc')
                    ->first();
                
                $lastMaintenanceKm = $lastMaintenance ? $lastMaintenance->maintenance_km : 0;
                if ($lastMaintenanceKm == 0) {
                    $maxKm = \App\Models\Maintenance::where('vehicle_id', $vehicle->id)
                        ->whereNotNull('maintenance_km')
                        ->max('maintenance_km');
                    $lastMaintenanceKm = $maxKm ? (int) $maxKm : 0;
                }

                $lastMaintenanceType = $lastMaintenance ? $lastMaintenance->maintenance_type : 'N/A';
                $nextDueKm = ($lastMaintenanceKm > 0) ? ($lastMaintenanceKm + 5000) : 5000;
                
                if ($currentKm >= $nextDueKm) {
                    $maintenanceAlerts[] = "Vehicle {$vehicle->plate_number} ({$vehicle->vehicle_name}) is due for maintenance! Current: {$currentKm} km, Last maintenance ({$lastMaintenanceType}) at {$lastMaintenanceKm} km.";
                }
            }
        }

        return view('fuel_slips.create', compact('boardmembers', 'maintenanceAlerts'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $request->validate([
            'boardmember_id' => 'required|exists:users,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_name' => 'required_without:vehicle_id|string|max:255',
            'plate_number' => 'required_without:vehicle_id|string|max:50',
            'liters' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'km_reading' => 'required|integer|min:0',
            'driver' => 'required|string|max:255',
            'date' => 'required|date',
            'prepared_by_name' => 'nullable|string|max:255',
            'approved_by_name' => 'nullable|string|max:255',
            'is_official_business' => 'nullable|boolean',
        ]);

        $selectedVehicle = null;
        if ($request->filled('vehicle_id')) {
            $selectedVehicle = Vehicle::find($request->vehicle_id);
        }

        // Check if official business - if true, skip fuel limit check
        $isOfficialBusiness = $request->boolean('is_official_business', false);
        
        // Debug: Log the checkbox value
        \Log::info('is_official_business raw: ' . $request->input('is_official_business'));
        \Log::info('is_official_business boolean: ' . ($isOfficialBusiness ? 'true' : 'false'));
        \Log::info('All request data: ' . json_encode($request->all()));
        
        // If not official business, check fuel limit
        if (!$isOfficialBusiness && $selectedVehicle && $selectedVehicle->monthly_fuel_limit > 0) {
            // Calculate current monthly usage for this vehicle and user
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            $monthlyLitersUsed = FuelSlip::where('user_id', $request->boardmember_id)
                ->where('vehicle_id', $selectedVehicle->id)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->sum('liters');
            
            // Check if adding this would exceed the limit
            if (($monthlyLitersUsed + $request->liters) > $selectedVehicle->monthly_fuel_limit) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Cannot add fuel slip: Monthly fuel limit (' . $selectedVehicle->monthly_fuel_limit . 'L) would be exceeded. Check "Official Business" to bypass fuel limit (will still deduct from budget).');
            }
        }

        FuelSlip::create([
            'user_id' => $request->boardmember_id,
            'vehicle_id' => $selectedVehicle?->id,
            'vehicle_name' => $selectedVehicle?->vehicle_name ?? $request->vehicle_name,
            'plate_number' => $selectedVehicle?->plate_number ?? $request->plate_number,
            'liters' => $request->liters,
            'unit_cost' => $request->unit_cost,
            'total_cost' => $request->total_cost,
            'km_reading' => $request->km_reading,
            'driver' => $request->driver,
            'control_number' => $this->generateUniqueControlNumber(),
            'date' => $request->date,
            'prepared_by_name' => $request->prepared_by_name,
            'approved_by_name' => $request->approved_by_name,
            'is_official_business' => $isOfficialBusiness,
        ]);

        // Update vehicle's current_km if vehicle exists
        if ($selectedVehicle) {
            $selectedVehicle->update(['current_km' => $request->km_reading]);
        }

        $redirectRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'boardmember.dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Fuel slip added successfully.');
    }

    public function exportPDF($id)
    {
        $fuelSlip = FuelSlip::findOrFail($id);

        if (auth()->user()->role === 'boardmember' && $fuelSlip->user_id !== auth()->id()) {
            abort(403);
        }

        return \Barryvdh\DomPDF\Facade\Pdf::loadView('fuel_slips.pdf_template', compact('fuelSlip'))
            ->download('fuel-slip-' . $fuelSlip->control_number . '.pdf');
    }

    public function viewPDF($id)
    {
        $fuelSlip = FuelSlip::findOrFail($id);

        if (auth()->user()->role === 'boardmember' && $fuelSlip->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate PDF and return as blob for iframe display
        return \Barryvdh\DomPDF\Facade\Pdf::loadView('fuel_slips.pdf_template', compact('fuelSlip'))
            ->stream('fuel-slip-' . $fuelSlip->control_number . '.pdf');
    }

}