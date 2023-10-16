<?php

namespace App\Filament\Resources\Quiz\ExamResource\Pages;

use App\Filament\Resources\Quiz\ExamResource;
use App\Models\Quiz\Exam;
use App\Filament\Widgets\ExamDetailWidget;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamResultExport;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Quiz\ExamResult;
use Filament\Actions;

class ExamDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.resources.quiz.exam-resource.pages.exam-detail';

    public ?Exam $exam;

    public $examId;

    public $count;

    public function mount($record): void
    {
        $this->exam = Exam::find($record);
        $this->examId = $record;
    }

    public function getTitle(): string
    {
        return __('Thống kê của ') . $this->exam->name;
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('excel')
                ->label('Xuất file excel')
                ->requiresConfirmation()
                ->action(fn () =>
                    Excel::download(new ExamResultExport($this->examId, $this->count), 'exam_statistical.xlsx')
                ),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExamDetailWidget::make([
                'count' => $this->count,
            ]),
        ];
    }

    public function table(Table $table): Table
    {
        $query = ExamResult::query()
            ->where('quiz_exam_id', $this->examId)
            ->orderBy('id', 'desc');

        $this->count = $query->count();

        $table->query($query);

        return $table
            ->columns([
                TextColumn::make('user.id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('user.name')
                    ->label('Tên')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('point')
                    ->label('Tổng điểm')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total_time')
                    ->label('Tổng thời gian')
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
