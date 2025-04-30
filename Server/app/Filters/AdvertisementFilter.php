<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;

class AdvertisementFilter
{
    public static function apply(Builder $query, array $filters):Builder
    {
        $filterMethods = [
            'ads_status' => 'filterByAdsStatus',
            'active_status' => 'filterByActiveStatus',
            'user_query' => 'filterByUser',
            'category_id' => 'filterByCategory',
            'city' => 'filterByCity',
            'type' => 'filterByType',
        ];
        foreach($filters as $name => $value)
        {
            if(!empty($value) && isset($filterMethods[$name]))
            {
                self::{ $filterMethods[$name] }($query, $value);
            }
        }
        return $query;
    }
    private static function filterByAdsStatus(Builder $query, $value):Builder
    {
        return $query->where('ads_status', $value);
    }
    private static function filterByActiveStatus(Builder $query, $value):Builder
    {
        return $query->where('active_status', $value);
    }
    private static function filterByUser(Builder $query, $value):Builder
    {
        return $query->whereHas('user', function($q) use ($value){
            $q->where('phone', $value)->orwhere('email', $value);
        });
    }
    private static function filterByCategory(Builder $query, $value):Builder
    {
        return $query->where('category_id', $value);
    }
    private static function filterByCity(Builder $query, $value):Builder
    {
        return $query->where('city', $value);
    }
    private static function filterByType(Builder $query, $value):Builder
    {
        return $query->where('type', $value);
    }

}
