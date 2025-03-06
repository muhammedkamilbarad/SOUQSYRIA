<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

class MarineAdvertisementRepository extends SpecificAdvertisementRepository
{
    public function createSpecific(Advertisement $advertisement, array $specificData): void
    {
        $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
        $advertisement->marineAdvertisement()->create($specificData['marine']);
    }
    public function getRelations(): array
    {
        return ['vehicleAdvertisement', 'marineAdvertisement'];
    }
}
