<?php
namespace App\Repositories\Advertisements;

use App\Enums\CategoryType;
use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AdvertisementCreationException;

class HouseAdvertisementRepository extends SpecificAdvertisementRepository
{
    public function createSpecific(Advertisement $advertisement, array $specificData): void
    {
        $advertisement->houseAdvertisement()->create($specificData['house']);
    }
    public function updateSpecific(Advertisement $advertisement, array $specificData): void
    {
        if(isset($specificData['house']))
        {
            $advertisement->houseAdvertisement()->update($specificData['house']);
        }
    }
    public function getRelations(): array
    {
        return ['houseAdvertisement'];
    }
}
