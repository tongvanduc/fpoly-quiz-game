<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ContestQuestionResource\Pages;
use App\Filament\Resources\Quiz\ContestQuestionResource\RelationManagers;
use App\Models\Quiz\ContestQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContestQuestionResource extends Resource
{
    protected static ?string $model = ContestQuestion::class;

    protected static ?string $slug = 'quiz/contest-questions';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContestQuestions::route('/'),
            'create' => Pages\CreateContestQuestion::route('/create'),
            'edit' => Pages\EditContestQuestion::route('/{record}/edit'),
        ];
    }
}
