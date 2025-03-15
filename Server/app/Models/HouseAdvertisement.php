<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseAdvertisement extends Model
{
    protected $primaryKey = 'advertisement_id';
    public $incrementing = false;

    protected $fillable = [
        'number_of_rooms',
        'number_of_bathrooms',
        'building_age',
        'square_meters',
        'floor'
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }
}
