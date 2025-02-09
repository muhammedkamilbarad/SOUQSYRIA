<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarineAdvertisement extends Model
{
    public function marineType()
    {
        return $this->belongsTo(MarineType::class, 'type_id');
    }
}