<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

abstract class SpecificAdvertisementRepository
{
    /**
     * Create specific advertisement data
     */
    abstract public function createSpecific(Advertisement $advertisement, array $specificData): void;

    /**
     * Get the relations to load
     */
    abstract public function getRelations(): array;
}
