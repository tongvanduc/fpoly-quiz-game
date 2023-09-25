<?php

namespace Database\Seeders;

use App\Models\Config\Campus;
use App\Models\Config\Major;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamAnswer;
use App\Models\Quiz\ExamQuestion;
use App\Models\Quiz\ExamResult;
use App\Models\User;
use Closure;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    const IMAGE_URL = 'https://source.unsplash.com/random/200x200/?img=1';

    public function run(): void
    {
        // Clear images
        Storage::deleteDirectory('public');


        // create campus
        $this->command->warn(PHP_EOL . 'Creating Campus ...');

        $campus = Campus::query()->create([
            'name' => 'Test',
            'code' => 'test',
            'status' => 1,
        ]);

        $this->command->info('Campus created.');


        // create major
        $this->command->warn(PHP_EOL . 'Creating Major ...');

        $major = Major::query()->create([
            'name' => 'Test',
            'code' => 'test',
            'status' => 1,
        ]);

        $this->command->info('Major created.');

        $campus_major_id = \DB::table('campus_majors')->first()->id;

        // Admin
        $this->command->warn(PHP_EOL . 'Creating Admin User...');
        $admin = User::factory(1)->create([
            'name' => 'Admin',
            'email' => 'admin@fpt.edu.com',
            'type_user' => TYPE_USER_ADMIN,
            'campus_major_id' => $campus_major_id,
        ]);

        $this->command->info('Admin User created.');

        // Student
        $this->command->warn(PHP_EOL . 'Creating Student User...');
        User::factory(10)->create([
            'type_user' => TYPE_USER_STUDENT,
            'campus_major_id' => $campus_major_id,
        ]);
        $this->command->info('Student User created.');

        // Exam
        $this->command->warn(PHP_EOL . 'Creating Exam ...');
        Exam::factory(10)->create([
            'campus_major_id' => $campus_major_id,
            'created_by' => $admin->first()->id,
        ]);
        $this->command->info('Exam user created.');

        $this->command->warn(PHP_EOL . 'Creating Exam Question ...');
        foreach (Exam::all() as $exam) {
            ExamQuestion::factory(10)->create([
                'quiz_exam_id' => $exam->id
            ]);
        }
        $this->command->info('Exam Question created.');

        $this->command->warn(PHP_EOL . 'Creating Exam Answer ...');
        foreach (ExamQuestion::all() as $examQuestion) {
            ExamAnswer::factory(4)->create([
                'quiz_exam_question_id' => $examQuestion->id,
            ]);
        }
        $this->command->info('Exam Answer created.');

        $this->command->warn(PHP_EOL . 'Creating Exam Result ...');
        foreach (User::all() as $user) {
            foreach (Exam::all() as $exam) {
                $data = [
                    'user_id' => $user->id,
                    'quiz_exam_id' => $exam->id,
                    'point' => 10,
                    'total_time' => 300,
                ];

                foreach (ExamQuestion::with('exam_answers')->where('quiz_exam_id', $exam->id)->get() as $examQuestion) {
                    $data['results'][$examQuestion->id] = [
                        $examQuestion->exam_answers->first()->id,
                        $examQuestion->exam_answers->last()->id,
                    ];
                }

                ExamResult::query()->create($data);
            }
        }

        $this->command->info('Exam Result created.');


//        // Blog
//        $this->command->warn(PHP_EOL . 'Creating blog categories...');
//        $blogCategories = $this->withProgressBar(20, fn () => BlogCategory::factory(1)
//            ->count(20)
//            ->create());
//        $this->command->info('Blog categories created.');
//
//        $this->command->warn(PHP_EOL . 'Creating blog authors and posts...');
//        $this->withProgressBar(20, fn () => Author::factory(1)
//            ->has(
//                Post::factory()->count(5)
//                    ->has(
//                        Comment::factory()->count(rand(5, 10))
//                            ->state(fn (array $attributes, Post $post) => ['customer_id' => $customers->random(1)->first()->id]),
//                    )
//                    ->state(fn (array $attributes, Author $author) => ['blog_category_id' => $blogCategories->random(1)->first()->id]),
//                'posts'
//            )
//            ->create());
//        $this->command->info('Blog authors and posts created.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
