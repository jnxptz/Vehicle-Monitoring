<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
