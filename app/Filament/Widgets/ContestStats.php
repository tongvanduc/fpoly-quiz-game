<?php

namespace App\Filament\Widgets;

use App\Models\Quiz\Contest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContestStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Contest', Contest::query()->count()),
        ];
    }
}
