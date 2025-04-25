<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function vehicleAdvertisements()
    {
        return $this->hasMany(VehicleAdvertisement::class, 'color_id');
    }//
}
