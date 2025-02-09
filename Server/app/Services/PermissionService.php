<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    public function getPermissionById(int $id): ?Model
    {
        try {
            return $this->permissionRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createPermission(array $data): Model
    {
        return $this->permissionRepository->create($data);
    }

    public function updatePermission(Model $permission, array $data): Model
    {
        return $this->permissionRepository->update($permission, $data);
    }

    public function deleteColor(Model $permission)
    {
        $this->permissionRepository->delete($permission);
    }
}
