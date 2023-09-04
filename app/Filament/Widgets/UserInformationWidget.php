<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserInformationWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public $user;

    protected function getStats(): array
    {
        return [
            Stat::make('Name', $this->user->name),
            Stat::make('Email', $this->user->email),
        ];
    }
}
