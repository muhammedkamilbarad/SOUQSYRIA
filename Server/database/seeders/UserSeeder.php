<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Ensure we have roles in the database
        $roleIds = Role::pluck('id')->toArray();
        
        // Create an admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'phone' => '1234567890',
                'is_verified' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role_id' => $roleIds[0] // Admin role
            ]
        );

        // Create an admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'phone' => '0987654321',
                'is_verified' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role_id' => $roleIds[1] // Manager role
            ]
        );

        // Create 20 random users
        for ($i = 0; $i < 20; $i++) {
            $email = $faker->unique()->safeEmail;
            $phone = $faker->unique()->numerify('##########');
            
            // Check if user with this email or phone exists
            while (User::where('email', $email)->orWhere('phone', $phone)->exists()) {
                $email = $faker->unique()->safeEmail;
                $phone = $faker->unique()->numerify('##########');
            }
            
            User::create([
                'name' => $faker->name,
                'email' => $email,
                'phone' => $phone,
                'is_verified' => $faker->boolean(80), // 80% chance of being verified
                'email_verified_at' => $faker->boolean(70) ? now() : null, // 70% chance of email verification
                'password' => Hash::make('password123'),
                'role_id' => $roleIds[2],
            ]);
        }
    }
}