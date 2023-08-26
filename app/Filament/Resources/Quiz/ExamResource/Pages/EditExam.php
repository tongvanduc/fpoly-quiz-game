<?php

namespace App\Filament\Resources\Quiz\ExamResource\Pages;

use App\Filament\Resources\Quiz\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Field;

class EditExam extends EditRecord
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
