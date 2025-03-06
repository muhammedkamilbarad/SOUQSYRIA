<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection; 


class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    

    public function getAllRoles(): Collection
    {
        return $this->roleRepository->getAllRolesWithPermissionsAndUserCount();
    }

    public function getRoleById(int $id)
    {
        try
        {
            return $this->roleRepository->getRoleWithPermissionsAndUserCount($id);
        }
        catch (RoleNotFoundException $e)
        {
            return null;
        }
    }

    public function createRole(array $data): Model
    {
        return $this->roleRepository->create($data);
    }

    public function updateRole(Model $role, array $data)
    {
        return $this->roleRepository->update($role, $data);
    }

    public function deleteRole(Model $role)
    {
        $this->roleRepository->delete($role);
    }
}