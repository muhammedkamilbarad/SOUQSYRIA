<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder;

class HomePageAdvertisementFilter
{
    private static $categoryFilterMap = [
        1 => 'filterLandAdvertisements',
        2 => 'filterHouseAdvertisements',
        3 => 'filterCarAdvertisements',
        4 => 'filterMarineAdvertisements',
        5 => 'filterMotorcycleAdvertisements',
    ];

    public static function apply(Builder $query, array $filters)
    {
        // Remove null or empty values from the filters array
        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');
        // Apply basic filters to the query
        self::applyBasicFilters($query, $filters);
        // Apply price filters
        self::applyPriceFilters($query, $filters);
        // Apply category filters
        self::applyCategoryFilters($query, $filters);
        // Apply sorting
        self::applySorting($query, $filters);
        return $query;
    }

    private static function applyBasicFilters(Builder $query, array $filters): void
    {
        $query->when(isset($filters['category']), fn($q) => $q->where('category_id', $filters['category']))
            ->when(isset($filters['type']), fn($q) => $q->where('type', $filters['type']))
            ->when(isset($filters['city']), fn($q) => $q->where('city', $filters['city']))
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->where(function ($query) use ($filters) {
                    $query->where('title', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            });
    }

    private static function applyPriceFilters(Builder $query, array $filters): void
    {
        if (isset($filters['minPrice']) && isset($filters['maxPrice'])) {
            $query->whereBetween('price', [$filters['minPrice'], $filters['maxPrice']]);
        } elseif (isset($filters['minPrice'])) {
            $query->where('price', '>=', $filters['minPrice']);
        } elseif (isset($filters['maxPrice'])) {
            $query->where('price', '<=', $filters['maxPrice']);
        }
    }

    private static function applySquareMetersFilter($q, array $filters): void
    {
        if (isset($filters['min_square_meters']) && isset($filters['max_square_meters'])) {
            $q->whereBetween('square_meters', [$filters['min_square_meters'], $filters['max_square_meters']]);
        } elseif (isset($filters['min_square_meters'])) {
            $q->where('square_meters', '>=', $filters['min_square_meters']);
        } elseif (isset($filters['max_square_meters'])) {
            $q->where('square_meters', '<=', $filters['max_square_meters']);
        }
    }


    private static function applyCategoryFilters(Builder $query, array $filters): void
    {
        if (isset($filters['category']) && isset(self::$categoryFilterMap[$filters['category']])) {
            $method = self::$categoryFilterMap[$filters['category']];
            self::$method($query, $filters);
        }
    }

    private static function applySorting(Builder $query, array $filters): void
    {
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $direction = $filters['sort_direction'] ?? 'desc';
        $allowedColumns = ['created_at', 'price'];
        if (in_array($sortBy, $allowedColumns)) {
            $query->orderBy($sortBy, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }
    }

    private static function filterVehicleAdvertisements(Builder $query, array $filters)
    {
        $query->whereHas('vehicleAdvertisement', function ($q) use ($filters) {
            $q->when(isset($filters['brand']), fn($q) => $q->where('brand_id', $filters['brand']))
            ->when(isset($filters['model']), fn($q) => $q->where('model_id', $filters['model']))
            ->when(isset($filters['color']), fn($q) => $q->where('color', $filters['color']))
            ->when(isset($filters['fuel_type']), fn($q) => $q->where('fuel_type', $filters['fuel_type']))
            ->when(isset($filters['transmission_type']), fn($q) => $q->where('transmission_type', $filters['transmission_type']))
            ->when(isset($filters['condition']), fn($q) => $q->where('condition', $filters['condition']))
            ->when(isset($filters['year']), fn($q) => $q->where('year', $filters['year']));
        });
    }

    private static function filterMotorcycleAdvertisements(Builder $query, array $filters)
    {
        self::filterVehicleAdvertisements($query, $filters);
        $query->whereHas('motorcycleAdvertisement', function ($q) use ($filters) {
            $q->when(isset($filters['motorcycle_type']), fn($q) => $q->where('motorcycle_type', $filters['motorcycle_type']));
            $q->when(isset($filters['cooling_type']), fn($q) => $q->where('cooling_type', $filters['cooling_type']));
        });
    }

    private static function filterMarineAdvertisements(Builder $query, array $filters)
    {
        self::filterVehicleAdvertisements($query, $filters);
        $query->whereHas('marineAdvertisement', function ($q) use ($filters) {
            $q->when(isset($filters['marine_type']), fn($q) => $q->where('marine_type', $filters['marine_type']));
        });
    }

    private static function filterLandAdvertisements(Builder $query, array $filters)
    {
        $query->whereHas('landAdvertisement', function ($q) use ($filters) {
            self::applySquareMetersFilter($q, $filters);
        });
    }

    private static function filterCarAdvertisements(Builder $query, array $filters)
    {
        self::filterVehicleAdvertisements($query, $filters);
        $query->whereHas('carAdvertisement', function ($q) use ($filters) {
            $q->when(isset($filters['car_type']), fn($q) => $q->where('car_type', $filters['car_type']));
        });
    }

    private static function filterHouseAdvertisements(Builder $query, array $filters)
    {
        $query->whereHas('houseAdvertisement', function ($q) use ($filters) {
            $q->when(isset($filters['house_type']), fn($q) => $q->where('house_type', $filters['house_type']))
            ->when(isset($filters['number_of_rooms']), fn($q) => $q->where('number_of_rooms', $filters['number_of_rooms']));
            self::applySquareMetersFilter($q, $filters);
        });
    }
}
