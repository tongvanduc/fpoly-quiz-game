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
            Stat::make('Total Exam', function () {
                $examQuery = Exam::query();

                if (!is_super_admin()) {

                    $examQuery->where('major_id', auth()->user()->major_id);

                }

                return $examQuery->count();
            }),
        ];
    }
}
