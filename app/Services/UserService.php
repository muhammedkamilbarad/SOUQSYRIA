<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Services\ImageUploadService;


class UserService
{
    protected $userRepository;
    protected $imageUploadService;
    protected $imagePath = "profiles";

    public function __construct(UserRepository $userRepository, ImageUploadService $imageUploadService)
    {
        $this->userRepository = $userRepository;
        $this->imageUploadService = $imageUploadService;
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

    public function updateProfile(Model $user, array $data): Model
    {
        \Log::info('update profile service');
        // Handle the image
        if (isset($data['image'])) {
            $image_url = $this->imagePath . '/' . $user->id;

            $status = $this->imageUploadService->deleteImage($image_url);
            \Log::info('Status ==> ' . $status);
            $data['image'] = $this->imageUploadService->uploadImage(
                $image_url,
                $data['image']
            );
            \Log::info('image ==> ' . $data['image']);
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

    public function getAllUsers(array $filters = [], array $searchTerms = [], int $page = 1, int $perPage = 15): array
    {
        return $this->userRepository->getUsersWithFiltersAndSearch($filters, $searchTerms, $page, $perPage);
    }
}
