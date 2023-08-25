<?php

namespace App\Filament\Resources\Quiz\ContestResource\Pages;

use App\Filament\Resources\Quiz\ContestResource;
use Filament\Pages\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListContests extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ContestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('5xl')
                ->slideOver(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ContestResource::getWidgets();
    }
}
