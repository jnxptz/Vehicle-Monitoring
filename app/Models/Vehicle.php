<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['bm_id', 'office_id', 'plate_number', 'vehicle_name', 'driver', 'monthly_fuel_limit', 'current_km'];

    // Vehicle belongs to an office
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function bm()
    {
        return $this->belongsTo(User::class, 'bm_id'); // User table as BM
    }

    public function fuelSlips()
    {
        return $this->hasMany(FuelSlip::class);
    }

    public function latestFuelSlip()
    {
        return $this->hasOne(FuelSlip::class)->latest();
    }

    public function getLatestKm()
    {
        $latestFuelSlip = $this->latestFuelSlip()->first();
        return $latestFuelSlip ? $latestFuelSlip->km_reading : $this->current_km;
    }
}
