<?php

namespace App\Enums;

enum TransmissionType: int
{
    case MANUAL = 1;
    case AUTOMATIC = 2;
    case SEMI_AUTOMATIC = 3;
}
