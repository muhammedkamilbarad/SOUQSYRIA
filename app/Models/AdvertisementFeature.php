<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementFeature extends Model
{
    use HasFactory;

    protected $fillable = ['advertisement_id', 'feature_id'];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
