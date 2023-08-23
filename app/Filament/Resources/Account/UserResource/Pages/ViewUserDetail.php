<?php

namespace App\Filament\Resources\Account\UserResource\Pages;

use App\Filament\Resources\Account\UserResource;
use App\Filament\Widgets\UserInformationWidget;
use App\Models\Quiz\ContestResult;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ViewUserDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.account.user-resource.pages.view-user-detail';

    public ?User $user;

    private object $contests;

    public function mount($record): void
    {
        $this->user = User::query()->findOrFail($record);
        $this->contests = ContestResult::query()->where('user_id', $this->user->id)->get();
    }

    public function getTitle(): string
    {
        return $this->user->name;
    }


    protected function getHeaderWidgets(): array
    {
        return [
            UserInformationWidget::make([
                'user' => $this->user,
            ]),
        ];
    }

    public function table(Table $table): Table
    {
        $query = ContestResult::query()
            ->whereIn(DB::raw('(user_id, quiz_contest_id, point)'), function ($query) {
                $query->select('user_id', 'quiz_contest_id', DB::raw('MAX(point)'))
                    ->from('quiz_contest_results')
                    ->groupBy('user_id', 'quiz_contest_id');
            })->where('user_id', $this->user->id);

        $table->query($query);

        return $table
            ->columns([
                TextColumn::make('contest.name')
                    ->label('Contest')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('point')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_time')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                ViewAction::make()
//                    Phần cmt của Vinh thầy đừng xóa nhé
//                    ->url(function ($record) {
//                    return route('filament.admin.resources.account.users.contest_result', ['record' => $this->user->id, 'related' => $record->id]);
//                }),
            ]);
    }

}
