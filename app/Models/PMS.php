<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PMS extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'last_km',
        'next_due_km',
        'alert'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
