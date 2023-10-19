<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ExamQuestionResource\Pages;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamQuestion;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExamQuestionResource extends Resource
{
    protected static ?string $model = ExamQuestion::class;

    protected static ?string $slug = 'quiz/exam/questions';

    protected static ?string $label = 'Questions';

    protected static ?string $recordTitleAttribute = 'title_origin';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Questions';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Question')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('quiz_exam_id')
                                    ->label('Exam')
                                    ->options(Exam::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Trạng thái kích hoạt')
                                    ->helperText('Câu hỏi này sẽ bị ẩn khỏi bài kiểm tra')
                                    ->inline(false)
                                    ->default(true),
                            ])
                            ->columns(2),

                        Forms\Components\Textarea::make('title_origin')
                            ->columnSpan('full')
                            ->maxLength(255)
                            ->placeholder('Nhập tiêu đề câu hỏi vào đây')
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Ảnh')
                            ->image()
                            ->columnSpan('full'),

                        Forms\Components\Textarea::make('title_extra')
                            ->placeholder('Thêm nội dung câu hỏi (nếu có)')
                            ->columnSpan('full'),

                        Forms\Components\Textarea::make('`explain`')
                            ->placeholder('Giải thích câu trả lời nếu có')
                            ->columnSpan('full'),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('exam_answers')
                            ->label('Answers')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('content')
                                    ->required()
                                    ->label('Đáp án')
                                    ->placeholder('Nội dung trả lời')
                                    ->columnSpan([
                                        'md' => 5,
                                    ]),

                                Forms\Components\TextInput::make('order')
                                    ->gt(0)
                                    ->label('Sắp xếp')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(255)
                                    ->placeholder('Nhập số thứ tự')
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->required()
                                    ->columnSpan([
                                        'md' => 1,
                                    ]),

                                Forms\Components\Toggle::make('is_true')
                                    ->label('Là đúng')
                                    ->helperText('Câu trả lời chính xác')
                                    ->default(false)
                                    ->columnSpan([
                                        'md' => 2,
                                    ]),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Trạng thái kích hoạt')
                                    ->helperText('Câu trả lời này sẽ bị ẩn khỏi câu hỏi.')
                                    ->default(true)
                                    ->columnSpan([
                                        'md' => 2,
                                    ]),
                            ])
                            ->deletable(false)
                            ->defaultItems(1)
                            ->columns([
                                'md' => 10
                            ])
                            ->columnSpan('full')
                            ->required(),
                    ]),
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

                Tables\Columns\TextColumn::make('title_origin')
                    ->label('Tiêu đề câu hỏi')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->defaultImageUrl(asset('image/no-image-icon.png'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title_extra')
                    ->label('Tiêu đề bổ sung')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái kích hoạt')
                    ->searchable()
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái kích hoạt')
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
            'index' => Pages\ListExamQuestions::route('/'),
            'create' => Pages\CreateExamQuestion::route('/create'),
            'edit' => Pages\EditExamQuestion::route('/{record}/edit'),
        ];
    }
}
