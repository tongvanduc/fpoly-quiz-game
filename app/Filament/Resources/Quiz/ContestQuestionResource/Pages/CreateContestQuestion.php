<?php

namespace App\Filament\Resources\Quiz\ContestQuestionResource\Pages;

use App\Filament\Resources\Quiz\ContestQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContestQuestion extends CreateRecord
{
    protected static string $resource = ContestQuestionResource::class;
}
