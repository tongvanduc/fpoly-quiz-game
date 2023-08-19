<?php

namespace App\Filament\Resources\Quiz\ContestQuestionResource\Pages;

use App\Filament\Resources\Quiz\ContestQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContestQuestion extends EditRecord
{
    protected static string $resource = ContestQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
