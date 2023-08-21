<?php

namespace Database\Factories\Quiz;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContestQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_origin' => fake()->text(100),
            'image' => rand(0, 1) ? fake()->imageUrl() : null,
            'title_extra' => rand(0, 1) ? fake()->text(100) : null,
            'explain' => rand(0, 1) ? fake()->text() : null,
            'is_active' => true,
        ];
    }
}
