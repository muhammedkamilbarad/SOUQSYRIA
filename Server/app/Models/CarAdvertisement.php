<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarAdvertisement extends Model
{
    protected $primaryKey = 'advertisement_id';
    public $incrementing = false;

    protected $fillable = [
        'seats',
        'doors',
        'seats_color'
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }
    public function vehicleAdvertisement(): HasOne
    {
        return $this->hasOne(VehicleAdvertisement::class, 'advertisement_id', 'advertisement_id');
    }
}
