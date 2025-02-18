<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Subscribing;


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
}
