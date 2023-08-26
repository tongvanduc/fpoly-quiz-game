<?php

namespace App\Filament\Resources\Quiz\ExamResource\Pages;

use App\Filament\Resources\Quiz\ExamResource;
use App\Models\Quiz\ExamAnswer;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class ShowAnswers extends Page
{

    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.resources.quiz.exam-resource.pages.show-answers';

    public ?ExamAnswer $examAnswer;

    private object $exams;

    public $id;

    public function mount(){

    }
}
