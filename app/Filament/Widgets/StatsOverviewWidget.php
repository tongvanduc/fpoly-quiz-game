<?php

namespace App\Filament\Widgets;

use App\Models\Quiz\Exam;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Exam', Exam::query()->count()),
            Stat::make('Total Student', User::query()->where('type_user', TYPE_USER_STUDENT)->count()),
        ];
    }
}
