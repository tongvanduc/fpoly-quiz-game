<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\QuizExamLevelsResource\Pages;
use App\Filament\Resources\Quiz\QuizExamLevelsResource\RelationManagers;
use App\Models\Quiz\QuizExamLevels;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizExamLevelsResource extends Resource
{
    protected static ?string $model = QuizExamLevels::class;

    protected static ?string $slug = 'quiz/exam/quiz_exam_levels';

    protected static ?string $label = 'Quiz Exam Levels';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Quiz Exam Levels';

    protected static ?int $navigationSort = 1;

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
                        ->placeholder('Name of the exam')
                        ->live(onBlur: true),

                        Forms\Components\Toggle::make('is_active')
                        ->label('Is active')
                        ->helperText('This quiz quiz level will be hidden from the test.')
                        ->default(true)
                        ->columnSpan([
                            'md' => 2,
                        ]),

                    ])
                    ->columns(2),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                    Tables\Columns\IconColumn::make('is_active')
                    ->label('Is active')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),    

            ])
            ->defaultSort('id', 'desc')

            ->filters([

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Is active')
                    ->boolean()
                    ->trueLabel('Active')
                    ->falseLabel('Passive')
                    ->native(true),

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
            'index' => Pages\ListQuizExamLevels::route('/'),
            'create' => Pages\CreateQuizExamLevels::route('/create'),
            'edit' => Pages\EditQuizExamLevels::route('/{record}/edit'),
        ];
    }    
}
