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
            ['name' => 'package_status_change'],
            ['name' => 'update_package'],
            ['name' => 'view_ad'],
            ['name' => 'create_ad'],
            ['name' => 'update_ad'],
            ['name' => 'delete_ad'],
            ['name' => 'process_ad'],
            ['name' => 'view_complaint'],
            ['name' => 'delete_complaint'],
            ['name' => 'view_color'],
            ['name' => 'create_color'],
            ['name' => 'delete_color'],
            ['name' => 'update_color'],
            ['name' => 'view_marineTypes'],
            ['name' => 'create_marineTypes'],
            ['name' => 'delete_marineTypes'],
            ['name' => 'update_marineTypes'],
            ['name' => 'view_vehicleModels'],
            ['name' => 'create_vehicleModels'],
            ['name' => 'delete_vehicleModels'],
            ['name' => 'update_vehicleModels'],
            ['name' => 'view_vehicleBrands'],
            ['name' => 'create_vehicleBrands'],
            ['name' => 'delete_vehicleBrands'],
            ['name' => 'update_vehicleBrands'],
            ['name' => 'process_subscription_requests'],
            ['name' => 'process_subscription_requests'],
            ['name' => 'view_subscription_requests'],
            ['name' => 'view_subscription'],
            ['name' => 'create_subscription'],
            ['name' => 'update_subscription'],
            ['name' => 'delete_subscription'],
            ['name' => 'view_feature'],
            ['name' => 'create_feature'],
            ['name' => 'update_feature'],
            ['name' => 'delete_feature'],
            ['name' => 'view_feature_group'],
            ['name' => 'create_feature_group'],
            ['name' => 'update_feature_group'],
            ['name' => 'delete_feature_group'],
            ['name' => 'view_category_features']
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
