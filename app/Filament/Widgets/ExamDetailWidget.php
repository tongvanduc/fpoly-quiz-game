<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExamDetailWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public $count;

    protected function getStats(): array
    {
        return [
            Stat::make('Number of participants', $this->count),
        ];
    }
}
