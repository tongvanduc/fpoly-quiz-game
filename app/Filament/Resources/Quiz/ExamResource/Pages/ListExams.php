<?php

namespace App\Filament\Resources\Quiz\ExamResource\Pages;

use App\Filament\Resources\Quiz\ExamResource;
use Filament\Pages\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListExams extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ExamResource::class;

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
        return ExamResource::getWidgets();
    }
}
