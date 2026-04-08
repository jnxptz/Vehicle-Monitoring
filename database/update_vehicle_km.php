<?php
/**
 * Script to update vehicle current_km from latest fuel slip data
 * Run this to fix existing vehicles that show 0 km
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Vehicle;
use App\Models\FuelSlip;

// Get all vehicles
$vehicles = Vehicle::all();
$updatedCount = 0;

echo "Updating vehicle KM readings...\n\n";

foreach ($vehicles as $vehicle) {
    // Get the latest fuel slip for this vehicle
    $latestFuelSlip = FuelSlip::where('vehicle_id', $vehicle->id)
        ->orderBy('km_reading', 'desc')
        ->first();
    
    if ($latestFuelSlip) {
        $vehicle->update(['current_km' => $latestFuelSlip->km_reading]);
        echo "✓ Vehicle {$vehicle->plate_number}: Updated to {$latestFuelSlip->km_reading} km\n";
        $updatedCount++;
    } else {
        echo "- Vehicle {$vehicle->plate_number}: No fuel slips found (kept at {$vehicle->current_km} km)\n";
    }
}

echo "\n✅ Done! Updated {$updatedCount} vehicles.\n";
