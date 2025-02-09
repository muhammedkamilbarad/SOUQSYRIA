<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advs_id');
    }
}
