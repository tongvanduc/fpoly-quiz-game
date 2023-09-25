<?php

namespace App\Filament\Widgets;

use App\Models\Quiz\Exam;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExamStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Exam', Exam::query()->where('campus_major_id', auth()->user()->campus_major_id)->count()),
        ];
    }
}
