<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'city',
        'price',
        'location',
        'category_id',
        'user_id',
        'ads_status',
        'active_status',
        'video_url',
        'type',
    ];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($advertisement) {
            $advertisement->slug = self::generateSlug($advertisement);
        });
        static::updating(function ($advertisement) {
            if ($advertisement->isDirty('title')) {
                $advertisement->slug = self::generateSlug($advertisement);
            }
        });
    }

    public static function generateSlug($advertisement)
    {
        $title = self::processTitle($advertisement->title);
        return  $title;
    }

    public static function processTitle($title)
    {
        $words = explode(' ', $title);
        $processedWords = [];
        foreach ($words as $word) {
            if (preg_match('/\p{Arabic}/u', $word)) {
                $processedWords[] = $word;
            } else {
                $processedWords[] = Str::slug($word);
            }
        }
        return implode('-', array_filter($processedWords));
    }

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
