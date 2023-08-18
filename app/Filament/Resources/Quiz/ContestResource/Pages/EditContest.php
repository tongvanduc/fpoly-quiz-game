<?php

namespace App\Filament\Resources\Quiz\ContestResource\Pages;

use App\Filament\Resources\Quiz\ContestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContest extends EditRecord
{
    protected static string $resource = ContestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
