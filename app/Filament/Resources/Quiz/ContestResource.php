<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ContestResource\Pages;
use App\Filament\Resources\Quiz\ContestResource\Widgets\ContestStats;
use App\Models\Quiz\Contest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ContestResource extends Resource
{
    protected static ?string $model = Contest::class;

    protected static ?string $slug = 'quiz/contests';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Quiz';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Contests';

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
                                    ->placeholder('Name of the contest')
                                    ->live(onBlur: true),

                                Forms\Components\TextInput::make('code')
                                    ->default(function (){
                                        return strtoupper(Str::random(8));
                                    })
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(Contest::class, 'code', ignoreRecord: true),
                            ])
                            ->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('max_working_time')
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

                        Forms\Components\Section::make('Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Image')
                                    ->required()
                                    ->image()
                                    ->disableLabel(),
                            ])
                            ->collapsible()
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Is active')
                                    ->helperText('This contest will be hidden from all list contests.')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('start_date')
                                    ->label('Start date')
                                    ->default(now())
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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
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

                Tables\Columns\TextColumn::make('max_working_time')
                    ->label('Max working time')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_of_tries')
                    ->label('Max of tries')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Is active')
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

                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('start_date_form')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('start_date_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date_form'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_date_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
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

    public static function getWidgets(): array
    {
        return [
            ContestStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContests::route('/'),
            'create' => Pages\CreateContest::route('/create'),
            'edit' => Pages\EditContest::route('/{record}/edit'),
        ];
    }
}
