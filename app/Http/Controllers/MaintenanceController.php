<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceController extends Controller
{
    
    private function generateUniqueCallOfNo(): string
    {
        $datePart = now()->format('Ymd');

        for ($attempt = 0; $attempt < 10; $attempt++) {
            $randomPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $candidate = "MN-{$datePart}-{$randomPart}";

            if (!Maintenance::where('call_of_no', $candidate)->exists()) {
                return $candidate;
            }
        }

        return 'MN-' . now()->format('YmdHisv') . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            // Get offices for filter dropdown
            $offices = \App\Models\Office::orderBy('name')->get();
            
            // Apply office filter if selected
            $query = User::where('role', 'boardmember')
                ->whereNotNull('office_id')
                ->with(['office', 'vehicles' => function($q){ 
                    $q->with(['maintenances' => function($mq){ $mq->latest(); }]); 
                }])
                ->orderBy('name');
                
            if (request('office')) {
                $query->where('office_id', request('office'));
            }
            
            $boardmembers = $query->get();
            $maintenances = Maintenance::with('vehicle')->latest()->get(); // Keep for backward compatibility
            $maintenanceAlerts = []; // No alerts for admin
        } else {
            // For boardmember: fetch only their vehicles' maintenances and check maintenance alerts
            $maintenances = Maintenance::with('vehicle')
                ->whereHas('vehicle', function ($q) use ($user) {
                    $q->where('bm_id', $user->id);
                })
                ->latest()
                ->get();
            $boardmembers = collect(); // Empty collection for boardmember view
            $offices = collect();
            
            // Check for maintenance alerts for boardmember's vehicles
            $maintenanceAlerts = [];
            $vehicles = $user->vehicles()->get();
            foreach ($vehicles as $vehicle) {
                // Get current KM from vehicle or latest fuel slip
                $currentKm = $vehicle->current_km ?? 0;
                $latestFuelSlip = \App\Models\FuelSlip::where('vehicle_id', $vehicle->id)
                    ->orderBy('km_reading', 'desc')
                    ->first();
                if ($latestFuelSlip && $latestFuelSlip->km_reading > $currentKm) {
                    $currentKm = $latestFuelSlip->km_reading;
                }

                // Get last maintenance
                $lastMaintenance = Maintenance::where('vehicle_id', $vehicle->id)
                    ->orderBy('date', 'desc')
                    ->first();
                
                $lastMaintenanceKm = $lastMaintenance ? $lastMaintenance->maintenance_km : 0;
                if ($lastMaintenanceKm == 0) {
                    $maxKm = Maintenance::where('vehicle_id', $vehicle->id)
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
        
        $vehicles = Vehicle::orderBy('plate_number')->get();
        
        return view('maintenances.index', compact('maintenances', 'vehicles', 'boardmembers', 'offices', 'maintenanceAlerts'));
    }

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        if ($vehicles->isEmpty()) {
            return redirect()->route('maintenances.index')
                ->with('error', 'Add at least one vehicle before creating a maintenance record.');
        }
        return view('maintenances.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'maintenance_type' => 'required|in:preventive,repair',
            'maintenance_km' => 'required|integer|min:0',
            'operation' => 'required|string|max:65535',
            'cost' => 'required|numeric|min:0',
            'conduct' => 'required|string|max:255',
            'date' => 'required|date',
            'prepared_by_name' => 'nullable|string|max:255',
            'approved_by_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
        ]);

        $data = array_merge($validated, [
            'call_of_no' => $this->generateUniqueCallOfNo(),
            'photo' => null,
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('maintenance_photos', 'public');
        }

        try {
            Maintenance::create($data);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Could not save maintenance. Please try again.');
        }

        return redirect()->route('maintenances.index')->with('success', 'Maintenance recorded.');
    }

    public function exportPDF($id)
    {
        $maintenance = Maintenance::with(['vehicle', 'vehicle.bm'])->findOrFail($id);

        
        if (auth()->user()->role === 'boardmember' && $maintenance->vehicle?->bm_id !== auth()->id()) {
            abort(403);
        }

        return Pdf::loadView('maintenances.pdf', compact('maintenance'))
            ->download('maintenance-' . $maintenance->call_of_no . '.pdf');
    }

    public function viewPDF($id)
    {
        $maintenance = Maintenance::with(['vehicle', 'vehicle.bm'])->findOrFail($id);

        if (auth()->user()->role === 'boardmember' && $maintenance->vehicle?->bm_id !== auth()->id()) {
            abort(403);
        }

        // Generate PDF and return as blob for iframe display
        return Pdf::loadView('maintenances.pdf', compact('maintenance'))
            ->stream('maintenance-' . $maintenance->call_of_no . '.pdf');
    }
}
