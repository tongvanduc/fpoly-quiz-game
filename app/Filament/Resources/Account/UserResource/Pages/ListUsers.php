<?php

namespace App\Filament\Resources\Account\UserResource\Pages;

use App\Filament\Resources\Account\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('5xl')
                ->slideOver(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All'),
            'Super-admin' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type_user', TYPE_USER_SUPER_ADMIN)),
            'Admin' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type_user', TYPE_USER_ADMIN)),
            'Student' => ListRecords\Tab::make()->query(fn ($query) => $query->where('type_user', TYPE_USER_STUDENT)),
        ];
    }
}
