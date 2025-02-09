<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarineType extends Model
{
    protected $fillable = ["question","answer"];

    public function marineAdvertisements()
    {
        return $this->hasMany(MarineAdvertisement::class, 'type_id');
    }
}
