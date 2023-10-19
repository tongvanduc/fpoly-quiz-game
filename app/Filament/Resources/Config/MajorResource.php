<?php

namespace App\Filament\Resources\Config;

use App\Filament\Resources\Config\MajorResource\Pages;
use App\Filament\Resources\Config\MajorResource\RelationManagers;
use App\Models\Config\Major;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MajorResource extends Resource
{
    protected static ?string $model = Major::class;

    protected static ?string $navigationGroup = 'Config';

    protected static ?string $label = 'Majors';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->autofocus()
                                    ->maxValue(255)
                                    ->placeholder('Name of the campus')
                                    ->live(onBlur: true),


                                Forms\Components\TextInput::make('code')
                                    ->placeholder('Code of the campus')
                                    ->maxValue(10)
                                    ->required()
                                    ->unique(Major::class, 'code', ignoreRecord: true),

                                Forms\Components\Toggle::make('status')
                                    ->label('Status')
                                    ->default(true),

                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ToggleColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('campus.name')
                    ->label('Campus')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMajors::route('/'),
        ];
    }
}
