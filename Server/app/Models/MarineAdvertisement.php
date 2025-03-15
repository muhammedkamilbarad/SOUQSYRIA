<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarineAdvertisement extends Model
{
    protected $primaryKey = 'advertisement_id';
    public $incrementing = false;

    protected $fillable = [
        'marine_type',
        'length',
        'max_capacity'
    ];
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function vehicleAdvertisement(): HasOne
    {
        return $this->hasOne(VehicleAdvertisement::class, 'advertisement_id', 'advertisement_id');
    }
    public function marineType()
    {
        return $this->belongsTo(MarineType::class, 'type_id');
    }
}
