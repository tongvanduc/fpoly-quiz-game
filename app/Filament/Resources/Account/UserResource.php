<?php

namespace App\Filament\Resources\Account;

use App\Filament\Resources\Account\UserResource\Pages;
use App\Models\Config\Major;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Hidden::make('uuid')
                                    ->default(Str::uuid()->toString()),

                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->autofocus()
                                    ->maxValue(255)
                                    ->required()
                                    ->placeholder('Enter name here'),

                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->disabledOn('edit')
                                    ->unique(User::class, 'email', ignoreRecord: true)
                                    ->email()
                                    ->maxValue(255)
                                    ->required()
                                    ->placeholder('Enter email here'),

                                Forms\Components\TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->confirmed()
                                    ->minValue(8)
                                    ->maxValue(255)
                                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                    ->required()
                                    ->helperText('Mật khẩu ít nhất phải có 8 ký tự!')
                                    ->placeholder('Enter password here'),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label('Re-enter the password')
                                    ->password()
                                    ->same('password')
                                    ->maxValue(255)
                                    ->required()
                                    ->dehydrated(false)
                                    ->placeholder('Enter re-enter the password here'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('type_user')
                                    ->options([
                                        TYPE_USER_ADMIN => "Admin",
                                        TYPE_USER_SUPER_ADMIN => "Super admin",
                                    ])
                                    ->live()
                                    ->default(TYPE_USER_ADMIN)
                                    ->required()
                                    ->native(false)
                                    ->label('Type user')
                                    ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                                        ->getContainer()
                                        ->getComponent('select')
                                        ->getChildComponentContainer()
                                        ->fill()),

                                Forms\Components\Grid::make()
                                    ->schema(fn(Forms\Get $get): array => match ($get('type_user')) {
                                        TYPE_USER_ADMIN => [
                                            Forms\Components\Select::make('major_id')
                                                ->label('Major')
                                                ->required()
                                                ->options(Major::all()->pluck('name', 'id'))
                                                ->native(false),
                                        ],
                                        default => [],
                                    })
                                    ->columns(1)
                                    ->key('select'),

                                Forms\Components\Hidden::make('email_verified_at')
                                    ->default(date('Y-m-d H:i:s')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => is_super_admin() ? $query : $query->where('major_id',
                auth()->user()->major_id))
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

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('major.campus.name')
                    ->label('Campus')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('major.name')
                    ->label('Major')
                    ->default('')
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
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email verification')
                    ->nullable()
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->modalWidth('5xl')
                    ->slideOver()
                    ->visible(fn ($record) => $record->type_user == "admin")
                    ->form([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->autofocus()
                                                    ->maxValue(255)
                                                    ->required()
                                                    ->placeholder('Enter name here'),

                                                Forms\Components\TextInput::make('email')
                                                    ->label('Email')
                                                    ->disabledOn('edit')
                                                    ->unique(User::class, 'email', ignoreRecord: true)
                                                    ->email()
                                                    ->maxValue(255)
                                                    ->required()
                                                    ->placeholder('Enter email here'),

                                                Forms\Components\TextInput::make('password')
                                                    ->label('Password')
                                                    ->password()
                                                    ->confirmed()
                                                    ->minValue(8)
                                                    ->nullable()
                                                    ->maxValue(255)
                                                    ->dehydrateStateUsing(fn($record, $state) =>
                                                        $state ? $record->password : Hash::make($state))
                                                    ->helperText('Mật khẩu ít nhất phải có 8 ký tự!')
                                                    ->placeholder('Enter password here'),

                                                Forms\Components\TextInput::make('password_confirmation')
                                                    ->label('Re-enter the password')
                                                    ->password()
                                                    ->nullable()
                                                    ->same('password')
                                                    ->maxValue(255)
                                                    ->dehydrated(false)
                                                    ->placeholder('Enter re-enter the password here'),
                                            ])
                                            ->columns(2),
                                    ])
                                    ->columnSpan(['lg' => 2]),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Section::make()
                                            ->schema([
                                                Forms\Components\Select::make('type_user')
                                                    ->options([
                                                        TYPE_USER_ADMIN => "Admin",
                                                        TYPE_USER_SUPER_ADMIN => "Super admin",
                                                    ])
                                                    ->live()
                                                    ->default(TYPE_USER_ADMIN)
                                                    ->required()
                                                    ->native(false)
                                                    ->label('Type user')
                                                    ->afterStateUpdated(fn(Forms\Components\Select $component
                                                    ) => $component
                                                        ->getContainer()
                                                        ->getComponent('select')
                                                        ->getChildComponentContainer()
                                                        ->fill()),

                                                Forms\Components\Grid::make()
                                                    ->schema(fn(Forms\Get $get): array => match ($get('type_user')) {
                                                        TYPE_USER_ADMIN => [
                                                            Forms\Components\Select::make('major_id')
                                                                ->label('Major')
                                                                ->disabled()
                                                                ->default(
                                                                    fn($record) => $record->major->name
                                                                )
                                                                ->options(
                                                                    fn($record) => [
                                                                        $record->major->id => $record->major->name
                                                                    ]
                                                                )
                                                                ->native(false),
                                                        ],
                                                        TYPE_USER_SUPER_ADMIN => [
                                                            Forms\Components\Hidden::make('major_id')
                                                                ->default(null)
                                                        ],
                                                        default => [],
                                                    })
                                                    ->columns(1)
                                                    ->key('select'),
                                            ]),
                                    ])
                                    ->columnSpan(['lg' => 1]),
                            ])
                            ->columns(3),
                    ])
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
            'exam_result' => Pages\ViewUserExamResult::route('/{record}/exam_result/{related}'),
        ];
    }
}
