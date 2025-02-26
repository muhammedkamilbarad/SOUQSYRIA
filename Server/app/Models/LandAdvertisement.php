<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandAdvertisement extends Model
{
    protected $primaryKey = 'advertisement_id';
    public $incrementing = false;

    protected $fillable = [
        'square_meters'
    ];

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }
}
