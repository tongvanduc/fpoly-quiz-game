<?php

namespace App\Filament\Resources\Config\MajorResource\Pages;

use App\Filament\Resources\Config\CampusResource;
use App\Filament\Resources\Config\MajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCampuses extends ManageRecords
{
    protected static string $resource = CampusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
