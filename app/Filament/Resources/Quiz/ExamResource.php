<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ExamResource\Pages;
use App\Filament\Widgets\ExamStats;
use App\Models\Config\Campus;
use App\Models\Config\CampusMajor;
use App\Models\Config\Major;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamQuestion;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $slug = 'quiz/exams';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $label = 'Exams';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Exams';

    protected static ?int $navigationSort = 0;

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
                                    ->placeholder('Name of the exam')
                                    ->live(onBlur: true),

                                Forms\Components\TextInput::make('code')
                                    ->default(function () {
                                        return strtoupper(Str::random(8));
                                    })
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Exam::class, 'code', ignoreRecord: true),
                            ])
                            ->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('timer')
                                            ->gt(0)
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->placeholder('Enter in seconds')
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->required(),

                                        Forms\Components\TextInput::make('max_of_tries')
                                            ->gt(0)
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->placeholder('Maximum number of attempts')
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->image(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Is active')
                                    ->helperText('This exam will be hidden from all list exams.')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('start_date')
                                    ->label('Start date')
                                    ->default(now())
                                    ->afterOrEqual(now()->format('d-m-Y H'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\DateTimePicker::make('end_date')
                                    ->label('End date')
                                    ->default(now())
                                    ->seconds(false)
                                    ->afterOrEqual('start_date')
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('campus_major_id', auth()->user()->campus_major_id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->defaultImageUrl(asset('image/no-image-icon.png'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start date')
                    ->date('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('End date')
                    ->date('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('timer')
                    ->label('Timer')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_of_tries')
                    ->label('Max of tries')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is active')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('campus_major.campus.name')
                    ->label('Campus')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('campus_major.major.name')
                    ->label('Major')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->default('')
                    ->searchable()
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

                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('start_date_form')
                            ->placeholder(fn($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('start_date_until')
                            ->placeholder(fn($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date_form'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_date_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['start_date_form'] ?? null) {
                            $indicators['start_date_form'] = 'Start date from ' . \Illuminate\Support\Carbon::parse($data['start_date_form'])->toFormattedDateString();
                        }
                        if ($data['start_date_until'] ?? null) {
                            $indicators['start_date_until'] = 'Start date until ' . Carbon::parse($data['start_date_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('question')
                    ->label('Questions')
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                    ->modalSubmitAction(false)
                    ->slideOver()
                    ->modalWidth('7xl')
                    ->modalContent(
                        function (Exam $exam) {
                            $questions = ExamQuestion::where('quiz_exam_id', $exam->id)
                                ->orderBy('id', 'desc')
                                ->get();

                            return view('filament.shows.question',
                                ['questions' => $questions, 'label' => $exam->name]);
                        }
                    ),

                Tables\Actions\EditAction::make()
                    ->modalWidth('5xl')
                    ->slideOver(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('5xl')
                    ->slideOver(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ExamStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
        ];
    }
}
