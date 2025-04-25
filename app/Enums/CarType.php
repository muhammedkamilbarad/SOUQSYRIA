<?php
namespace App\Enums;

enum CarType: int
{
    case SEDAN = 1;
    case COUPE = 2;
    case HATCHBACK = 3;
    case SPORT_CAR = 4;
    case BOX_TRUCK = 5;
    case FLATBED_TRUCK = 6;
    case VAN_CAR = 7;
    case TANK_TRUCK = 8;
    case PICKUP = 9;
}

