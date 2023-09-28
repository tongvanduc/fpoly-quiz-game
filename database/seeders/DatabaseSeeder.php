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
            'name' => 'Campus Test',
            'code' => 'cpte',
            'status' => 1,
        ]);

        $this->command->info('Campus created.');


        // create major
        $this->command->warn(PHP_EOL . 'Creating Major ...');

        $major = Major::query()->create([
            'name' => 'Major Test',
            'code' => 'mate',
            'campus_id' => $campus->id,
            'status' => 1,
        ]);

        $this->command->info('Major created.');

        // Admin
        $this->command->warn(PHP_EOL . 'Creating Super Admin User...');
        $superAdmins = User::factory(1)->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@fpt.edu.com',
            'type_user' => TYPE_USER_SUPER_ADMIN,
        ]);
        $this->command->info('Super Admin User created.');

        // Admin
        $this->command->warn(PHP_EOL . 'Creating Admin User...');
        $admins = User::factory(1)->create([
            'name' => 'Admin',
            'email' => 'admin@fpt.edu.com',
            'type_user' => TYPE_USER_ADMIN,
            'major_id' => $major->id,
        ]);
        $this->command->info('Admin User created.');
        // Student
        $this->command->warn(PHP_EOL . 'Creating Student User...');
        User::factory(10)->create([
            'type_user' => TYPE_USER_STUDENT,
            'major_id' => $major->id,
        ]);
        $this->command->info('Student User created.');

        // Exam
        $this->command->warn(PHP_EOL . 'Creating Exam ...');
        Exam::factory(10)->create([
            'major_id' => $major->id,
            'created_by' => $admins->first()->id,
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
                'quiz_exam_question_id' => $examQuestion->id
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
