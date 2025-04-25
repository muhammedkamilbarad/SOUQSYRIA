<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;
use Illuminate\Support\Facades\Log;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                "name" => "Free",
                "properties" => "Free pakcage",
                "price" => 0,
                "max_of_ads" => 5,
                "period" => 90,
            ],
            [
                "name" => "Premium",
                "properties" => "this is a premium package",
                "price" => 99.99,
                "max_of_ads" => 5,
                "period" => 15,
            ],
            [
                "name" => "Seliver",
                "properties" => "this is a seliver package",
                "price" => 149.99,
                "max_of_ads" => 10,
                "period" => 25,
            ],
            [
                "name" => "Gold",
                "properties" => "this is a premium package",
                "price" => 249.99,
                "max_of_ads" => 40,
                "period" => 90,
            ]
        ];

        Log::info('Starting Package Seeder');

        foreach ($packages as $packageData) {
            try {
                $existingPackage = Package::where('name', $packageData['name'])->first();

                if ($existingPackage) {
                    Log::info("Package '{$packageData['name']}' already exists, updating...");
                    $existingPackage->update($packageData);
                } else {
                    Log::info("Creating new Package: '{$packageData['name']}'");
                    Package::create($packageData);  // Changed from Color::create to Package::create
                }
            } catch (\Exception $e) {
                Log::error("Failed to seed package '{$packageData['name']}': " . $e->getMessage());
                throw $e;
            }
        }

        Log::info('PackageSeeder completed');
    }
}