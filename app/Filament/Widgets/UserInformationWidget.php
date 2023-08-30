<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class UserInformationWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public $user;



    protected function getStats(): array
    {
        return [
            Stat::make('Name', function (): HtmlString {
                $name = "<p style='font-size: 18px;'>{$this->user->name}</p>";
                return new HtmlString($name);
            }),
            Stat::make('Email', function (): HtmlString {
                $email = "<p style='font-size: 18px;'>{$this->user->email}</p>";
                return new HtmlString($email);
            }),
        ];
    }
}
