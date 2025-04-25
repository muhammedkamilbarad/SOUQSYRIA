<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ["name","properties","price","max_of_ads", "period", "is_active"];
    public function users()
    {
        return $this->belongsToMany(User::class, 'subscribings')->withPivot('remaining_ads', 'purchase_date', 'expiry_date');
    }

    public function subscribings()
    {
        return $this->hasMany(Subscribing::class);
    }
}
