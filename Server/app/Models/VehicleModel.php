<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleModel extends Model
{
    use HasFactory;
    
    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function vehicleAdvertisements()
    {
        return $this->hasMany(VehicleAdvertisement::class, 'model_id');
    }
}