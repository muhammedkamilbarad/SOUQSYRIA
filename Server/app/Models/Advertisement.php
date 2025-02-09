<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
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
}