<?php

namespace Database\Factories\Quiz;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'image' => fake()->imageUrl(),
            'code' => strtoupper(Str::random(8)),
            'start_date' => date('Y-m-d H:i'),
            'end_date' => date('Y-m-d H:i', strtotime('+30 days')),
            'timer' => 30,
            'max_of_tries' => 3,
            'is_active' => true,
        ];
    }
}
