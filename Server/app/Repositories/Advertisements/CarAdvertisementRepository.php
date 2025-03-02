<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

class  CarAdvertisementRepository extends SpecificAdvertisementRepository
{
    public function createSpecific(Advertisement $advertisement, array $specificData): void
    {
        $advertisement->vehicleAdvertisement()->create($specificData['vehicle']);
        $advertisement->carAdvertisement()->create($specificData['car']);
    }
    public function getRelations(): array
    {
        return ['vehicleAdvertisement','carAdvertisement'];
    }
}
