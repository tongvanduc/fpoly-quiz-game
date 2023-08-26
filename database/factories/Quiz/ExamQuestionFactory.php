<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\ExamQuestion;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExamQuestionFactory extends Factory
{
    protected $model = ExamQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_origin' => fake()->text(100),
            'image' => rand(0, 1) ? $this->createImage() : null,
            'title_extra' => rand(0, 1) ? fake()->text(100) : null,
            'explain' => rand(0, 1) ? fake()->text() : null,
            'is_active' => true,
        ];
    }

    public function createImage(): ?string
    {
        try {
            $image = file_get_contents(DatabaseSeeder::IMAGE_URL);
        } catch (Throwable $exception) {
            return null;
        }

        $filename = Str::uuid() . '.jpg';

        Storage::disk('public')->put($filename, $image);

        return $filename;
    }
}
