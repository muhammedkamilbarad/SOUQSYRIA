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

            [
                'name' => 'Super Admin',
                'permissions' => $validPermissions,
                'is_editable' => false,
                'is_deleteable' => false
            ],
            [
                'name' => 'Manager',
                'permissions' => Permission::whereIn('name', ['view_user', 'view_role', 'view_permission', 'view_faq', 'view_package'])->pluck('id')->toArray(),
                'is_editable' => false,
                'is_deleteable' => false
            ],
            [
                'name' => 'Normal user',
                'permissions' => Permission::whereIn('name', ['view_ad'])->pluck('id')->toArray(),
                'is_editable' => false,
                'is_deleteable' => false
            ]
        ];

        foreach ($roles as $role) {
            // Check if role already exists
            if (Role::where('name', $role['name'])->exists()) {
                Log::info("Skipping role '{$role['name']}' as it already exists.");
                continue;
            }

            // For non-admin roles, filter out invalid permission IDs
            if ($role['name'] !== 'Super Admin') {
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