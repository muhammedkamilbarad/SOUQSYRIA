<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'view_user'],
            ['name' => 'create_user'],
            ['name' => 'delete_user'],
            ['name' => 'update_user'],
            ['name' => 'view_role'],
            ['name' => 'create_role'],
            ['name' => 'delete_role'],
            ['name' => 'update_role'],
            ['name' => 'view_permission'],
            ['name' => 'view_faq'],
            ['name' => 'create_faq'],
            ['name' => 'delete_faq'],
            ['name' => 'update_faq'],
            ['name' => 'view_package'],
            ['name' => 'create_package'],
            ['name' => 'delete_package'],
            ['name' => 'update_package'],
            ['name' => 'view_ad'],
            ['name' => 'create_ad'],
            ['name' => 'update_ad'],
            ['name' => 'delete_ad'],
        ];

        Log::info('Starting Permission Seeder');

        foreach ($permissions as $permissionData) {
            try {
                $existingPermission = Permission::where('name', $permissionData['name'])->first();

                if ($existingPermission) {
                    Log::info("Permission '{$permissionData['name']}' already exists, skipping...");
                } else {
                    Log::info("Creating new Permission: '{$permissionData['name']}'");
                    Permission::create($permissionData);
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed permission '{$permissionData['name']}': " . $e->getMessage());
                throw $e;
            }
        }

        Log::info('PermissionSeeder completed');
    }
}
