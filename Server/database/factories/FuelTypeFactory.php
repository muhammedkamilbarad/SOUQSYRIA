<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FuelType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FuelType>
 */
class FuelTypeFactory extends Factory
{
    protected $model = FuelType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
        ];
    }
}
