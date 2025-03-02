<?php
namespace App\Enums;

enum CategoryType: int
{
    case LAND = 1;
    case HOUSE = 2;
    case CAR = 3;
    case MARINE = 4;
    case MOTORCYCLE = 5;

    public function label(): string
    {
        return match($this) {
            self::LAND => 'Land',
            self::HOUSE => 'House',
            self::CAR => 'Car',
            self::MARINE => 'Marine',
            self::MOTORCYCLE => 'Motorcycle',
        };
    }
}
