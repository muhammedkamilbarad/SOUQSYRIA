<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FavoriteService
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function addFavorite(int $userId, int $advertisementId): Model
    {
        return $this->favoriteRepository->addFavorite($userId, $advertisementId);
    }

    public function getUserFavorites(int $userId): Collection
    {
        return $this->favoriteRepository->getUserFavorites($userId);
    }

    public function removeFavorite(int $userId, int $advertisementId)
    {
        return $this->favoriteRepository->removeFavorite($userId, $advertisementId);
    }
}
