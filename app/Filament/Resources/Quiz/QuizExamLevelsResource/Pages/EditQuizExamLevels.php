<?php

namespace App\Filament\Resources\Quiz\QuizExamLevelsResource\Pages;

use App\Filament\Resources\Quiz\QuizExamLevelsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuizExamLevels extends EditRecord
{
    protected static string $resource = QuizExamLevelsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
