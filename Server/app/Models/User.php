<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role_id', 'image', 'phone', 'is_verified'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function favoriteAds()
    {
        return $this->belongsToMany(Advertisement::class, 'favoris', 'user_id', 'advs_id');
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'user_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'subscribings')->withPivot('remaining_ads', 'purchase_date', 'expiry_date');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}