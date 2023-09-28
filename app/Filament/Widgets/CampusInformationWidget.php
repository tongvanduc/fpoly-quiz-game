<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CampusInformationWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public object $campus;

    protected function getStats(): array
    {
        return [
            Stat::make('Name', $this->campus->name),
            Stat::make('Code', $this->campus->code),
            Stat::make('Status', $this->campus->status ? 'Active' : 'Inactive'),
        ];
    }
}
