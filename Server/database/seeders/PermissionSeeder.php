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
            ['name' => 'edit colors'],
            ['name' => 'edit marineTypes'],
            ['name' => 'edit vehiclemodels'],
            ['name' => 'edit vehiclebrands'],
            ['name' => 'edit categories'],
            ['name' => 'edit packages'],
            ['name' => 'edit popularQuestions'],
            ['name' => 'edit users'],
            ['name' => 'edit roles'],
            ['name' => 'edit subscribing'],
            ['name' => 'delete-Ad'],
            ['name' => 'view-Ad'],
            ['name' => 'add-user'],
            ['name' => 'edit-user'],
            ['name' => 'delete-user'],
            ['name' => 'view-user'],
            ['name' => 'add-color'],
            ['name' => 'edit-color'],
            ['name' => 'delete-color'],
            ['name' => 'view-color'],
            ['name' => 'add-role'],
            ['name' => 'edit-role'],
            ['name' => 'delete-role'],
            ['name' => 'view-role'],
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
