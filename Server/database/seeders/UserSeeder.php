<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        // Get roles by name
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $normalUserRole = Role::where('name', 'Normal user')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        
        // Create super admin user
        try
        {
            User::firstOrCreate(
                ['email' => 'superadmin@syr-souq.com'],
                [
                    'name' => 'Super Admin',
                    'phone' => '552347789',
                    'is_verified' => true,
                    'email_verified_at' => now(),
                    'password' => Hash::make('Sycoder2025@'),
                    'role_id' => $superAdminRole->id
                ]
            );
        }
        catch (\Exception $e) {
            // Log the error
            // Log::error('Failed to create super admin user: '. $e->getMessage());
        }

        // Create a manager user
        try
        {
            User::firstOrCreate(
                ['email' => 'manager@example.com'],
                [
                    'name' => 'Manager User',
                    'phone' => '0987654321',
                    'is_verified' => true,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'role_id' => $managerRole->id
                ]
            );
        }
        catch (\Exception $e) {
            // Log the error
            // Log::error('Failed to create manager user: '. $e->getMessage());
        }

        // Create 20 random users
        for ($i = 0; $i < 20; $i++) {
            $email = $faker->unique()->safeEmail;
            $phone = $faker->unique()->phoneNumber;
            
            // Check if user with this email or phone exists
            while (User::where('email', $email)->orWhere('phone', $phone)->exists()) {
                $email = $faker->unique()->safeEmail;
                $phone = $faker->unique()->phoneNumber;
            }
            
            try
            {
                $is_verified = $faker->boolean(70);
                User::create([
                    'name' => $faker->name,
                    'email' => $email,
                    'phone' => $phone,
                    'is_verified' => $is_verified,
                    'email_verified_at' => $is_verified ? now() : null,
                    'password' => Hash::make('password123'),
                    'role_id' => $normalUserRole->id,
                ]);
            }
            catch (\Exception $e) {
                // Log the error
                // Log::error('Failed to create user: '. $e->getMessage());
            }
        }
    }
}