<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\Exam;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'image' => $this->createImage(),
            'code' => strtoupper(Str::random(8)),
            'start_date' => date('Y-m-d H:i'),
            'end_date' => date('Y-m-d H:i', strtotime('+30 days')),
            'timer' => 30,
            'max_of_tries' => 3,
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
