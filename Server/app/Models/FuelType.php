<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FuelType extends Model
{
    use HasFactory;
    
    public function vehicleAdvertisements()
    {
        return $this->hasMany(VehicleAdvertisement::class, 'fuel_type_id');
    }
}