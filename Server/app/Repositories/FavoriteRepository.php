<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Favorite;
use App\Repositories\BaseRepository;

class FavoriteRepository extends BaseRepository
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function getAdvertisementCommonRelations(): array
    {
        return [
            'saleDetail',
            'rentDetail',
            'user',
            'category',
            'images',
            'vehicleAdvertisement' => function($query){
                $query->with('vehicleBrand', 'vehicleModel');
            },
            'landVehicleAttributes',
            'carAdvertisement',
            'motorcycleAdvertisement',
            'marineAdvertisement',
            'houseAdvertisement',
            'landAdvertisement',
            'features' => function($query){
                $query->with('featureGroup');
            },
        ];
    }

    public function addFavorite(int $userId, int $advertisementId)
    {
        $existingFavorite = Favorite::where('user_id', $userId)->where('advs_id', $advertisementId)->first();

        if (!$existingFavorite) {
            return $this->model->create([
                'user_id' => $userId,
                'advs_id' => $advertisementId
            ]);
        }

        return $existingFavorite;
    }

    public function getUserFavorites(int $userId)
    {
        return $this->model->with(['advertisement' => function($query) {
                                $query->with($this->getAdvertisementCommonRelations());
                            }])
                        ->where('user_id', $userId)
                        ->get();
    }

    public function removeFavorite(int $userId, int $advertisementId)
    {
        $favorite = $this->model->where('user_id', $userId)
                                ->where('advs_id', $advertisementId)
                                ->first();

        if ($favorite) {
            return $favorite->delete();
        }
        
        return false;
    }
}
