<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Subscribing;
use Carbon\Carbon;


class SubscribingRepository extends BaseRepository
{
    public function __construct(Subscribing $model)
    {
        parent::__construct($model);
    }

    // Geting all subscribings with user and package relationships.
    public function getAllWithUsersAndPackages(): Collection
    {
        return $this->model->with(['user', 'package'])->get();
    }

    // Geting a specific subscribing with user and package relationships.
    public function getByIdWithUserAndPackage(int $id): ?Model
    {
        return $this->model->with(['user', 'package'])->findOrFail($id);
    }

    public function getCurrentActiveSubscription(int $userId): ?Model
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('expiry_date', '>', Carbon::now())
            ->where('remaining_ads', '>', 0)
            ->with(['user', 'package'])
            ->first();
    }
}
