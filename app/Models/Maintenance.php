<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'maintenance_type',
        'maintenance_km',
        'operation',
        'cost',
        'conduct',
        'call_of_no',
        'date',
        'prepared_by_name',
        'approved_by_name',
        'photo'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
