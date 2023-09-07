<?php

namespace App\Filament\Resources\Quiz\ExamQuestionResource\Pages;

use App\Filament\Resources\Quiz\ExamQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateExamQuestion extends CreateRecord
{
    protected static string $resource = ExamQuestionResource::class;

    protected function sendCreatedNotificationAndRedirect(bool $shouldCreateAnotherInsteadOfRedirecting = true): void
    {
        $this->getCreatedNotification()?->send();

        $this->redirect($this->getRedirectUrl());
    }

    protected function getRedirectUrl(): string
    {
        return  $this->getResource()::getUrl('create');
    }
}
