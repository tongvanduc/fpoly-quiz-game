<?php

namespace App\Filament\Resources\Quiz\QuizExamLevelsResource\Pages;

use App\Filament\Resources\Quiz\QuizExamLevelsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuizExamLevels extends ListRecords
{
    protected static string $resource = QuizExamLevelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
