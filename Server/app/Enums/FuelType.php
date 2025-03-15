<?php
namespace App\Enums;

enum FuelType: int
{
    case PETROL = 1;
    case DIESEL = 2;
    case ELECTRIC = 3;
    case HYBRID = 4;
    case LPG = 5;
}

