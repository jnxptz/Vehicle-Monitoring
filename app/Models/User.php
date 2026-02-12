<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'office_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'bm_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function fuelSlips(): HasMany
    {
        return $this->hasMany(FuelSlip::class);
    }

    public function maintenances()
    {
        // Maintenances through vehicles
        return Maintenance::whereIn('vehicle_id', $this->vehicles()->pluck('id'))->latest();
    }
}
