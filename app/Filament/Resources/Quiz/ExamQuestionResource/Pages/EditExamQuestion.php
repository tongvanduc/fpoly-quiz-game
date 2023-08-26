<?php

namespace App\Filament\Resources\Quiz\ExamQuestionResource\Pages;

use App\Filament\Resources\Quiz\ExamQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamQuestion extends EditRecord
{
    protected static string $resource = ExamQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
