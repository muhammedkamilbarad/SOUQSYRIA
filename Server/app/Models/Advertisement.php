<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'city',
        'price',
        'currency',
        'location',
        'category_id',
        'user_id',
        'ads_status',
        'active_status',
        'image_upload_status',
        'type',
    ];

    public function saleDetail()
    {
        return $this->hasOne(SaleDetail::class, 'advertisement_id');
    }

    public function rentDetail()
    {
        return $this->hasOne(RentDetail::class, 'advertisement_id');
    }

    public function vehicleAdvertisement()
    {
        return $this->hasOne(VehicleAdvertisement::class);
    }
    public function carAdvertisement()
    {
        return $this->hasOne(CarAdvertisement::class);
    }
    public function motorcycleAdvertisement()
    {
        return $this->hasOne(MotorcycleAdvertisement::class);
    }
    public function marineAdvertisement()
    {
        return $this->hasOne(MarineAdvertisement::class);
    }
    public function houseAdvertisement()
    {
        return $this->hasOne(HouseAdvertisement::class);
    }
    public function landAdvertisement()
    {
        return $this->hasOne(LandAdvertisement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'advs_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'advs_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'advertisement_features');
    }
}
