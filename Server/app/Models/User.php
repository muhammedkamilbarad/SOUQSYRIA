<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $fillable = ['name', 'email', 'password', 'role_id', 'image', 'phone', 'is_verified', 'email_verified_at'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d\TH:i:s.u\Z',
    ];

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

    public function subscribings()
    {
        return $this->hasMany(Subscribing::class);
    }

    public function hasActiveSubscription()
    {
        return $this->subscribings()->where('expiry_date', '>', now())->where('remaining_ads', '>', 0)->exists();
    }

    public function permissions()
    {
        return $this->role->permissions();
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
