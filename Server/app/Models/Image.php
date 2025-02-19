<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['url', 'advs_id'];
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advs_id');
    }
}
