<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'vehicle_name',
        'plate_number',
        'liters',
        'cost',
        'km_reading',
        'driver',
        'control_number',
        'date'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
