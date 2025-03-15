<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertisement_id',
        'rental_period',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }
}
