<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ContestQuestionResource\Pages;

//use App\Filament\Resources\Quiz\ContestQuestionResource\RelationManagers;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action;

class ContestQuestionResource extends Resource
{
    protected static ?string $model = ContestQuestion::class;

    protected static ?string $slug = 'quiz/contest/questions';

    protected static ?string $recordTitleAttribute = 'title_origin';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Questions';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),

                        Forms\Components\Section::make('Answer')
                            ->schema(static::getFormSchema('answers')),
                    ])
                    ->columnSpan(['lg' => fn(?ContestQuestion $record) => $record === null ? 3 : 2]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_origin')
                    ->label('Title Origin')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title_extra')
                    ->label('Title Extra')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is active')
                    ->searchable()
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
            ])
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

    public static function getFormSchema(string $section = null): array
    {
        if ($section === 'answers') {
            return [
                Forms\Components\Repeater::make('contest_answers')
                    ->relationship('contest_answers')
                    ->schema([
                        Forms\Components\TextInput::make('Content')
                            ->required()
                            ->placeholder('Answer content')
                            ->columnSpan([
                                'md' => 5,
                            ]),

                        Forms\Components\TextInput::make('Order')
                            ->gt(0)
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(255)
                            ->placeholder('Enter order')
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                            ->required()
                            ->columnSpan([
                                'md' => 2,
                            ]),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Is active')
                            ->helperText('This answer will be hidden from the question.')
                            ->default(true)
                            ->columnSpan([
                                'md' => 2,
                            ]),
                    ])
                    ->deletable(false)
                    ->disableLabel()
                    ->defaultItems(1)
                    ->columns([
                        'md' => 10,
                    ])
                    ->required(),
            ];
        }

        return [
            Forms\Components\Select::make('quiz_contest_id')
                ->relationship('Contests', 'name')
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Is active')
                ->helperText('This question will be hidden from the contest')
                ->inline(false)
                ->default(true),

            Forms\Components\MarkdownEditor::make('title_origin')
                ->columnSpan('full')
                ->placeholder('Enter question title here')
                ->required(),

            Forms\Components\FileUpload::make('image')
                ->label('Image')
                ->image()
                ->columnSpan('full'),

            Forms\Components\MarkdownEditor::make('title_extra')
                ->placeholder('Add more questions if any')
                ->columnSpan('full'),

            Forms\Components\MarkdownEditor::make('explain')
                ->placeholder('Explain the answer if any')
                ->columnSpan('full'),
        ];
    }
}
