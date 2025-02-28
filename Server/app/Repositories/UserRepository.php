<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;


class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    // Geting all users with thier role
    public function getAllWithRoles(): Collection
    {
        return $this->model->with(['role'])->get();
    }

    // Geting specific users with his role
    public function getUserWithRole(int $id): User
    {
        return $this->model->with(['role'])->findOrFail($id);
    }
    public function findTrashed(int $id): ?Model
    {
        return $this->model::onlyTrashed()->find($id);
    }
}