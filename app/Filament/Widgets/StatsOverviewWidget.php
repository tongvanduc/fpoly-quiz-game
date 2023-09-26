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
            Stat::make('Total Exam', function () {
                $examQuery = Exam::query();

                if (!is_super_admin()) {

                    $examQuery->where('major_id', auth()->user()->major_id);

                }

                return $examQuery->count();
            }),
            Stat::make('Total Student', function () {
                    $studentQuery = User::query()->where('type_user', TYPE_USER_STUDENT);

                    if (!is_super_admin()) {

                        $studentQuery->where('major_id', auth()->user()->major_id);

                    }

                    return $studentQuery->count();
            }),
        ];
    }
}
