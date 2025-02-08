<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    use HasFactory;
    
    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'category_id');
    }

    public function vehicleBrands()
    {
        return $this->hasMany(VehicleBrand::class, 'category_id');
    }
}