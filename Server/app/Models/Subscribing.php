<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Package;

class Subscribing extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "package_id", "remaining_ads", "purchase_date", "expiry_date"];

    protected $casts = [
        'purchase_date' => 'datetime',
        'expiry_date' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}