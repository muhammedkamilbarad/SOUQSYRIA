<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Services\CategoryService;

class CategorySeeder extends Seeder
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function run(): void
    {
        $categories = [
            [
                'name' => 'land',
                'has_brand' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'house',
                'has_brand' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'car',
                'has_brand' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'marine',
                'has_brand' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'motorcycle',
                'has_brand' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $categoryData) {
            try {
                $existingCategory = $this->categoryService->getAllCategories()
                    ->firstWhere('name', $categoryData['name']);

                if ($existingCategory) {
                    $this->categoryService->updateCategory($existingCategory, $categoryData);
                } else {
                    $this->categoryService->createCategory($categoryData);
                }
            } catch (\Exception $e) {
                \Log::error("Error seeding category {$categoryData['name']}: " . $e->getMessage());
            }
        }
    }
}
