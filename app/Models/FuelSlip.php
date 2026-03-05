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
        'unit_cost',
        'total_cost',
        'km_reading',
        'driver',
        'control_number',
        'date',
        'prepared_by_name',
        'approved_by_name'
    ];

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'liters' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total_cost, calculating it if not set
     */
    public function getTotalCostAttribute($value)
    {
        if ($value !== null && $value > 0) {
            return $value;
        }
        // Calculate from liters and unit_cost if total_cost is 0 or null
        $liters = $this->attributes['liters'] ?? 0;
        $unitCost = $this->attributes['unit_cost'] ?? 0;
        return $liters * $unitCost;
    }
}
