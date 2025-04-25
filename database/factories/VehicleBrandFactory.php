<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VehicleBrand;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleBrand>
 */
class VehicleBrandFactory extends Factory
{
    protected $model = VehicleBrand::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = DB::table('categories')->where('has_brand', true)->pluck('id')->toArray();

        // Here I make sure just categories whose has brand will be used
        if (empty($categories)) {
            echo "Error: all categories are not having has brand";
            return [];
        }

        return [
            'name' => $this->faker->word(),
            'category_id' => $this->faker->randomElement($categories),
        ];
    }
}
