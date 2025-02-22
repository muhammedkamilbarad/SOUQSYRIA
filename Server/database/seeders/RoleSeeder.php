<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\RoleService;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function run(): void
    {
        // Get all valid permission IDs
        $validPermissions = Permission::pluck('id')->toArray();

        $roles = [
            ['name' => 'User', 'permissions' => [1, 2]],
            ['name' => 'Admin', 'permissions' => $validPermissions], // Admin gets all permissions
            ['name' => 'Manager', 'permissions' => [1, 2, 3]],
        ];

        foreach ($roles as $role) {
            // Check if role already exists
            if (Role::where('name', $role['name'])->exists()) {
                Log::info("Skipping role '{$role['name']}' as it already exists.");
                continue;
            }

            // For non-admin roles, filter out invalid permission IDs
            if ($role['name'] !== 'Admin') {
                $role['permissions'] = array_intersect($role['permissions'], $validPermissions);
            }

            if (empty($role['permissions'])) {
                Log::warning("Skipping role '{$role['name']}' because no valid permissions were found.");
                continue;
            }

            $this->roleService->createRole($role);
        }
    }
}