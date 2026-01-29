<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['bm_id', 'plate_number', 'monthly_fuel_limit', 'current_km'];

    public function bm()
    {
        return $this->belongsTo(User::class, 'bm_id'); // User table as BM
    }

    public function fuelSlips()
    {
        return $this->hasMany(FuelSlip::class);
    }
}
