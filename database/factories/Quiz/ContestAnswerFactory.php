<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\ContestAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContestAnswerFactory extends Factory
{
    protected $model = ContestAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->text(50),
            'order' => rand(1, 4),
            'is_true' => rand(0, 1),
            'is_active' => true,
        ];
    }
}
