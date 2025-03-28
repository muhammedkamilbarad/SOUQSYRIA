<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
    {
        // Injecting the Role model and call the parent constructor
        parent::__construct($model);
    }

    public function getRoleWithPermissionsAndUserCount(int $id)
    {
        return $this->model->with(['permissions'])
        ->withCount('users')
        ->findOrFail($id);
    }

    public function getAllRolesWithPermissionsAndUserCount()
    {
        return $this->model->with(['permissions'])->withCount('users')->get();
    }

    public function create(array $data): Model
    {
        // Validate that `permissions` is present and not empty
        // (You can also do this in a Form Request)

        // Create the role
        $role = parent::create([
            'name' => $data['name'],
            'is_editable' => $data['is_editable'],
            'is_deleteable' => $data['is_deleteable'],
        ]);

        /*
        a sync() method that will “synchronize” the pivot table (role_permissions) 
        with the provided list of IDs. In other words, it will ensure the pivot 
        table for that role has exactly those permission IDs and remove any that 
        are not in the list.
        */
        $role->permissions()->sync($data['permissions']);

        return $role->load('permissions');
    }

    public function update(Model $model, array $data): Model
    {
        // Use the same logic as before, but the `$model` is now of type `Model`
        $permissions = $data['permissions'] ?? null;

        // Updating the role
        parent::update($model, [
            'name' => $data['name'] ?? $model->name,
            'is_editable' => $data['is_editable'] ?? $model->is_editable,
            'is_deleteable' => $data['is_deleteable'] ?? $model->is_deleteable,
        ]);

        // Synchronizing permissions if provided
        if (!is_null($permissions)) {
            $model->permissions()->sync($permissions);
        }

        return $model->load('permissions');
    }


    // Delete a given role. The pivot table rows will be removed
    // automatically due to the 'onDelete('cascade')' on foreign keys
    
}
