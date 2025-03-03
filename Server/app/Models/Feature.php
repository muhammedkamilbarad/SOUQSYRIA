<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = ['name','category_id'];


    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_feature');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
