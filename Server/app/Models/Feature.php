<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = ['name','feature_group_id'];


    public function advertisements()
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_feature');
    }

    public function featureGroup()
    {
        return $this->belongsTo(FeatureGroup::class);
    }
}
