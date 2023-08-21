<?php

namespace App\Filament\Resources\Account;

use App\Filament\Resources\Account\UserResource\Pages;
//use App\Filament\Resources\Account\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components;

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
                    ->getStateUsing(fn (User $record): string => $record->email_verified_at?->isPast() ? 'Verified' : 'Unverified')
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\Group::make([
                                    Components\TextEntry::make('title'),
                                    Components\TextEntry::make('slug'),
                                    Components\TextEntry::make('published_at')
                                        ->badge()
                                        ->date()
                                        ->color('success'),
                                ]),
                                Components\Group::make([
                                    Components\TextEntry::make('author.name'),
                                    Components\TextEntry::make('category.name'),
                                    Components\TextEntry::make('tags')
                                        ->badge()
                                        ->getStateUsing(fn () => ['one', 'two', 'three', 'four']),
                                ]),
                            ]),
                        Components\ImageEntry::make('image')
                            ->hiddenLabel()
                            ->grow(false),

                    ]),
                    Components\Section::make('Content')
                    ->schema([
                        Components\TextEntry::make('content')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
