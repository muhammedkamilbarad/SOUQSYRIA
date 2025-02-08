<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'city_id');
    }
}
