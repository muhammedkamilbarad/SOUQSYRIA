<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserWithRoleById(int $id): ?Model
    {
        try {
            return $this->userRepository->getUserWithRole($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getUserById(int $id): ?Model
    {
        try
        {
            return $this->userRepository->getById($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    public function createUser(array $data): Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        // set email_verified_at date if the email is verified
        if (isset($data['is_verified']) && $data['is_verified'] === true) {
            $data['email_verified_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }

        $user = $this->userRepository->create($data);
        
        return $user;
    }

    public function updateUser(Model $user, array $data): Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($user, $data);
    }

    public function deleteUser(Model $user)
    {
        $user->forceDelete();
    }

    public function SoftDeleteUser(Model $user)
    {
        $this->userRepository->delete($user);
    }

    public function restoreUser(Model $user, int $id): ?Model
    {
        $user = $this->userRepository->findTrashed($id);
        if ($user) {
            $user->restore();
        }
        return $user;
    }

    public function getAllUsers(array $filters = [], array $searchTerms = [], ?string $cursor = null, int $limit = 15): array
    {
        return $this->userRepository->getUsersWithFiltersAndSearch($filters, $searchTerms, $cursor, $limit);
    }
}
