<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Office;
use App\Models\Vehicle;
use App\Models\FuelSlip;

class SampleOfficeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'admin')->first() ?? User::first();
        if (! $user) {
            $this->command->info('No users found. Create a user first.');
            return;
        }

        $office = Office::firstOrCreate([
            'name' => 'Test Office'
        ], [
            'address' => '123 Test St'
        ]);

        $vehicle = Vehicle::create([
            'bm_id' => $user->id,
            'office_id' => $office->id,
            'plate_number' => 'TST-123',
            'vehicle_name' => 'Test Vehicle',
            'driver' => 'Driver A',
            'monthly_fuel_limit' => null,
            'current_km' => 0,
        ]);

        FuelSlip::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'vehicle_name' => $vehicle->vehicle_name,
            'plate_number' => $vehicle->plate_number,
            'liters' => 10,
            'cost' => 500,
            'km_reading' => 1000,
            'driver' => $vehicle->driver,
            'control_number' => 'FS-TEST-001',
            'date' => now()->toDateString(),
        ]);

        $this->command->info('Sample office, vehicle, and fuel slip created.');
    }
}
