<?php

namespace App\Filament\Resources\Quiz\ExamQuestionResource\Pages;

use App\Filament\Resources\Quiz\ExamQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExamQuestions extends ListRecords
{
    protected static string $resource = ExamQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
