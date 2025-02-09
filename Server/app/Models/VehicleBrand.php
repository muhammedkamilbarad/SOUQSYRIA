<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleBrand extends Model
{
    use HasFactory;
    protected $fillable = ["name","category_id"];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

    public function vehicleAdvertisements()
    {
        return $this->hasMany(VehicleAdvertisement::class, 'brand_id');
    }
}
