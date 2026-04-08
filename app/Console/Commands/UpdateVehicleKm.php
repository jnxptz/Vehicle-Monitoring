<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\FuelSlip;

class UpdateVehicleKm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:update-km';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vehicle current_km from latest fuel slip data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vehicles = Vehicle::all();
        $updatedCount = 0;

        $this->info('Updating vehicle KM readings...\n');

        foreach ($vehicles as $vehicle) {
            $latestFuelSlip = FuelSlip::where('vehicle_id', $vehicle->id)
                ->orderBy('km_reading', 'desc')
                ->first();

            if ($latestFuelSlip) {
                $vehicle->update(['current_km' => $latestFuelSlip->km_reading]);
                $this->info("✓ {$vehicle->plate_number}: Updated to {$latestFuelSlip->km_reading} km");
                $updatedCount++;
            } else {
                $this->warn("- {$vehicle->plate_number}: No fuel slips found (kept at {$vehicle->current_km} km)");
            }
        }

        $this->info("\n✅ Done! Updated {$updatedCount} vehicles.");

        return 0;
    }
}
