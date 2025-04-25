<?php

namespace App\Enums;

enum CoolingType: string
{
    case AIR_COOLED = 'Air Cooled';
    case LIQUID_COOLED = 'Liquid Cooled';
    case OIL_COOLED = 'Oil Cooled';
    case HYBRID_COOLING = 'Hybrid Cooling';
}

