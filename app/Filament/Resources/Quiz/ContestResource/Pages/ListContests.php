<?php

namespace App\Filament\Resources\Quiz\ContestResource\Pages;

use App\Filament\Resources\Quiz\ContestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContests extends ListRecords
{
    protected static string $resource = ContestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
