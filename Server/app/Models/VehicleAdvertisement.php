<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAdvertisement extends Model
{
    protected $primaryKey = 'advertisement_id';
    public $incrementing = false;
    protected $fillable = [
        'color',
        'mileage',
        'year',
        'engine_capacity',
        'brand_id',
        'model_id',
        'fuel_type',
        'horsepower',
        'transmission_type',
        'condition',
        'cylinders'
    ];
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function vehicleBrand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function vehicleModel()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class, 'transmission_id');
    }
}
