<?php

namespace App\Http\Controllers;

use App\Models\FuelSlip;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $fuelSlips = $user->role === 'admin'
            ? FuelSlip::latest()->get()
            : FuelSlip::where('user_id', $user->id)->latest()->get();

        return view('fuel_slips.index', compact('fuelSlips'));
    }

    public function create()
    {
        return view('fuel_slips.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:50',
            'liters' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'km_reading' => 'required|integer|min:0',
            'driver' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        
        $vehicle = Vehicle::where('bm_id', Auth::id())->first();

        FuelSlip::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle?->id, 
            'vehicle_name' => $request->vehicle_name,
           
            'plate_number' => $vehicle?->plate_number ?? $request->plate_number,
            'liters' => $request->liters,
            'cost' => $request->cost,
            'km_reading' => $request->km_reading,
            'driver' => $request->driver,
            'control_number' => $this->generateUniqueControlNumber(),
            'date' => $request->date,
        ]);

        
        return redirect()
            ->route('boardmember.dashboard')
            ->with('success', 'Fuel slip added successfully and your dashboard has been updated.');
    }

    public function exportPDF($id)
{
    $fuelSlip = FuelSlip::findOrFail($id); 

   
    if (auth()->user()->role === 'boardmember' && $fuelSlip->user_id !== auth()->id()) {
        abort(403);
    }

    return \Barryvdh\DomPDF\Facade\Pdf::loadView('fuel_slips.pdf', compact('fuelSlip'))
        ->download('fuel-slip-' . $fuelSlip->control_number . '.pdf');
}

}
