<?php

namespace App\Filament\Resources\Quiz\ExamQuestionResource\Pages;

use App\Filament\Resources\Quiz\ExamQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExamQuestion extends CreateRecord
{
    protected static string $resource = ExamQuestionResource::class;
}
