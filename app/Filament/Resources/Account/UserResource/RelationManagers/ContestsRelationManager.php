<?php

namespace App\Filament\Resources\Account\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Infolists\Infolist;

class ContestsRelationManager extends RelationManager
{
    protected static string $relationship = 'contests';

    protected static ?string $recordTitleAttribute = 'name';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                //Ket qua cua contests
            ]);
    }

    public function table(Table $table): Table
    {
//        $stmt = DB::table('quiz_contest_results')
//                    ->select(DB::raw('MAX(point)'))
//                    ->where('user_id', 1)
//                    ->groupBy('point');
//
//        $query = DB::table('quiz_contest_results')
//                    ->select('user_id', 'quiz_contest_id', 'point')
//                    ->whereIn('point', $stmt);
//
//        $result = $query->get();
//
//        dd($result);
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('contest.name')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('point')
                    ->label('Point')
//                    ->getStateUsing(function (){
//
//                    })
                ,
                Tables\Columns\TextColumn::make('total_time')
                    ->label('Total time'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
