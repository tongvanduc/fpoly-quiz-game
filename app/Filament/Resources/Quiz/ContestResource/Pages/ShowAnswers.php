<?php

namespace App\Filament\Resources\Quiz\ContestResource\Pages;

use App\Filament\Resources\Quiz\ContestResource;
use App\Models\Quiz\ContestAnswer;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class ShowAnswers extends Page
{

    protected static string $resource = ContestResource::class;

    protected static string $view = 'filament.resources.quiz.contest-resource.pages.show-answers';

    public ?ContestAnswer $contestAnswer;

    private object $contests;

    public $id;

    public function mount(){

    }
}
