<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

class AdvertisementRepositoryFactory
{
    public static function create(CategoryType $category): SpecificAdvertisementRepository
    {
        return match($category)
        {
            CategoryType::LAND => new LandAdvertisementRepository(),
            CategoryType::HOUSE => new HouseAdvertisementRepository(),
            CategoryType::CAR => new CarAdvertisementRepository(),
            CategoryType::MARINE => new MarineAdvertisementRepository(),
            CategoryType::MOTORCYCLE => new MotorcycleAdvertisementRepository(),
            default => throw new \InvalidArgumentException('Invalid category type')
        };
    }
}
