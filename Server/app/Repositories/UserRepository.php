<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\AuthRepository;


class UserRepository extends BaseRepository
{

    protected $authRepository;

    public function __construct(User $model, AuthRepository $authRepository)
    {
        parent::__construct($model);

        $this->authRepository = $authRepository;
    }

    public function create(array $data): Model
    {
        $user = $this->model->create($data);

        // Refresh the user instance to get the latest data (including verified_at)
        $user->refresh();

        // Load the role relationship
        $user->load('role');

        return $user;
    }

    // Geting all users with thier role
    public function getAllWithRoles(): Collection
    {
        return $this->model->with(['role'])->get();
    }

    // Geting specific users with his role
    public function getUserWithRole(int $id): User
    {
        return $this->model->with(['role', 'role.permissions'])->findOrFail($id);
    }
    public function findTrashed(int $id): ?Model
    {
        return $this->model::onlyTrashed()->find($id);
    }
}