<?php

namespace App\Filament\Resources\Config\MajorResource\Pages;

use App\Filament\Resources\Config\MajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMajors extends ManageRecords
{
    protected static string $resource = MajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
