<?php

namespace App\Filament\Resources\Config\CampusResource\Pages;

use App\Filament\Resources\Config\CampusResource;
use App\Filament\Widgets\CampusInformationWidget;
use App\Models\Config\Campus;
use App\Models\Config\Major;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Collection;

class ViewCampus extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = CampusResource::class;

    protected static string $view = 'filament.resources.config.campus-resource.pages.view-campus-detail';

    public ?Campus $campus;

    public function mount($record): void
    {
        $this->campus = Campus::query()->findOrFail($record);
    }

    private function getFormAction(): array
    {
        return [
            Hidden::make('campus_id')
                ->default($this->campus->id),

            TextInput::make('name')
                ->required()
                ->autofocus()
                ->placeholder('Name')
                ->label('Name'),

            TextInput::make('code')
                ->required()
                ->autofocus()
                ->placeholder('Code')
                ->label('Code'),

            Toggle::make('status')
                ->label('Status')
                ->default(true),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form([
                    Hidden::make('campus_id')
                        ->default($this->campus->id),

                    ...$this->getFormAction()

                ])
                ->model(Major::class)
        ];
    }

    public function table(Table $table): Table
    {
        $query = Major::query()
            ->where('campus_id', $this->campus->id)
            ->orderBy('id', 'desc');

        $table->query($query);

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('status')
                    ->label('Status')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                ViewAction::make()
                    ->form($this->getFormAction()),
                EditAction::make()
                    ->form($this->getFormAction()),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CampusInformationWidget::make([
                'campus' => $this->campus,
            ]),
        ];
    }

}
