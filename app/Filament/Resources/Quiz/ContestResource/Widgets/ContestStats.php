<?php

namespace App\Filament\Resources\Quiz\ContestResource\Widgets;

use App\Filament\Resources\Quiz\ContestResource\Pages\ListContests;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ContestStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListContests::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Contests', $this->getPageTableQuery()->count()),
        ];
    }
}
