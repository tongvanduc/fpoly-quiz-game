<?php

namespace App\Filament\Resources\Account;

use App\Filament\Resources\Account\UserResource\Pages;
use App\Models\Quiz\ContestResult;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'account/users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Account';

    protected static ?string $label = 'Users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.resources.users.pages.view-user';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('email_verified_at')
                    ->label('Email verification')
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(fn(User $user
                    ): string => $user->email_verified_at?->isPast() ? 'Verified' : 'Unverified')
                    ->colors([
                        'success' => 'Verified',
                        'danger' => 'Unverified',
                    ]),

                Tables\Columns\BadgeColumn::make('type_user')
                    ->label('Type user')
                    ->sortable()
                    ->colors([
                        'success'
                    ])
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email verification')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUserDetail::route('/{record}'),
            'contest_result' => Pages\ViewUserContestResult::route('/{record}/contest_result/{related}'),
        ];
    }
}
