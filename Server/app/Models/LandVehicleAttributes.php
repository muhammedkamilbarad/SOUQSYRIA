<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandVehicleAttributes extends Model
{
    use HasFactory;
    protected $fillable = [
        'advertisement_id',
        'mileage',
        'transmission_type',
        'cylinders',
        'engine_capacity',
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

}
