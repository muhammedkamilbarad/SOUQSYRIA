<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

class MotorcycleAdvertisementRepository extends SpecificAdvertisementRepository
{
    public function createSpecific(Advertisement $advertisement, array $specificData): void
    {
        $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
        $advertisement->motorcycleAdvertisement()->create($specificData['motorcycle']);
    }
    public function getRelations(): array
    {
        return ['vehicleAdvertisement', 'motorcycleAdvertisement'];
    }
}
