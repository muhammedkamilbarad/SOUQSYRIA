<?php

namespace App\Enums;

enum MarineType: string {
    case MOTOR_YACHT = 'Motor Yacht';
    case SAILBOAT = 'Sailboat';
    case CATAMARAN = 'Catamaran';
    case SPEEDBOAT = 'Speedboat';
    case BOAT = 'Boat';
    case JET_SKI = 'Jet Ski';
    case DECK_BOAT = 'Deck Boat';
    case ROWBOAT = 'Rowboat';
    case CRUISE_BOAT = 'Cruise Boat';
    case GULET = 'Gulet';
    case FISHING_BOAT = 'Fishing Boat';
    case PASSENGER_SHIP = 'Passenger Ship';
    case CARGO_SHIP_TANKER = 'Cargo Ship / Tanker';
    case SERVICE_BOAT = 'Service Boat';
    case SUBMARINE = 'Submarine';
}

