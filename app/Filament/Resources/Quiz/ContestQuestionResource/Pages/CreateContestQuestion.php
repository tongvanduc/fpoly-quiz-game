<?php

namespace App\Filament\Resources\Quiz\ContestQuestionResource\Pages;

use App\Filament\Resources\Quiz\ContestQuestionResource;

use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateContestQuestion extends CreateRecord
{
    use HasWizard;

    protected static string $resource = ContestQuestionResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Question')
                ->schema([
                    Section::make()->schema(ContestQuestionResource::getFormSchema())->columns(),
                ]),

            Step::make('Answer')
                ->schema([
                    Section::make()->schema(ContestQuestionResource::getFormSchema('answers')),
                ]),
        ];
    }
}
