<?php

namespace Database\Seeders;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Address;
use App\Models\Blog\Author;
use App\Models\Blog\Category as BlogCategory;
use App\Models\Blog\Post;
use App\Models\Comment;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestAnswer;
use App\Models\Quiz\ContestQuestion;
use App\Models\Quiz\ContestResult;
use App\Models\Shop\Brand;
use App\Models\Shop\Category as ShopCategory;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Payment;
use App\Models\Shop\Product;
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

        // Admin
        $this->command->warn(PHP_EOL . 'Creating Admin User...');
        $this->withProgressBar(1, fn() => User::factory(1)->create([
            'name' => 'Admin',
            'email' => 'admin@fpt.edu.com',
            'type_user' => TYPE_USER_ADMIN,
        ]));
        $this->command->info('Admin User created.');

        // Student
        $this->command->warn(PHP_EOL . 'Creating Student User...');
        $this->withProgressBar(1, fn() => User::factory(10)->create([
            'type_user' => TYPE_USER_STUDENT,
        ]));
        $this->command->info('Student User created.');

        // Contest
        $this->command->warn(PHP_EOL . 'Creating Contest ...');
        $this->withProgressBar(1, fn() => Contest::factory(10)->create());
        $this->command->info('Contest user created.');

        $this->command->warn(PHP_EOL . 'Creating Contest Question ...');
        foreach (Contest::all() as $contest) {
            ContestQuestion::factory(10)->create([
                'quiz_contest_id' => $contest->id
            ]);
        }
        $this->command->info('Contest Question created.');

        $this->command->warn(PHP_EOL . 'Creating Contest Answer ...');
        foreach (ContestQuestion::all() as $contestQuestion) {
            for ($i = 0; $i < 10; $i++) {
                ContestAnswer::factory(4)->create([
                    'quiz_contest_question_id' => $contestQuestion->id
                ]);
            }
        }
        $this->command->info('Contest Answer created.');

        $this->command->warn(PHP_EOL . 'Creating Contest Result ...');
        foreach (User::all() as $user) {
            foreach (Contest::all() as $contest) {
                $data = [
                    'user_id' => $user->id,
                    'quiz_contest_id' => $contest->id,
                    'point' => 10,
                    'total_time' => 300,
                ];

                foreach (ContestQuestion::with('contest_answers')->where('quiz_contest_id', $contest->id)->get() as $contestQuestion) {
                    $data['results'][$contestQuestion->id] = [
                        $contestQuestion->contest_answers->first()->id,
                        $contestQuestion->contest_answers->last()->id,
                    ];
                }

                ContestResult::query()->create($data);
            }
        }

        $this->command->info('Contest Result created.');

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
