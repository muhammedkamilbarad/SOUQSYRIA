<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PopularQuestion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PopularQuestion>
 */
class PopularQuestionFactory extends Factory
{
    protected $model = PopularQuestion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->text(),
            'answer' => $this->faker->text()
        ];
    }
}
