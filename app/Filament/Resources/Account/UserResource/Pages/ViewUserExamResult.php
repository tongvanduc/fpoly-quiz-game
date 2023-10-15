<?php

namespace App\Filament\Resources\Account\UserResource\Pages;

use App\Filament\Custom\Forms\Components\CheckboxList;
use App\Filament\Resources\Account\UserResource;
use App\Models\Quiz\ExamQuestion;
use App\Models\Quiz\ExamResult;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\HtmlString;

class ViewUserExamResult extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.account.user-resource.pages.view-user-exam-result';

    protected ?ExamResult $examResult;

    public $examQuestion;

    protected ?User $user;

    protected int $counter;

    public $exam;

    public function __construct()
    {
        $this->counter = 0;
    }

    public function mount($record, $related): void
    {
        $this->user = User::query()->findOrFail($record);

        $this->examResult = ExamResult::query()
            ->where('user_id', $this->user->id)
            ->findOrFail($related);

        $this->exam = $this->examResult->exam;

        $this->examQuestion = ExamQuestion::with('exam_answers_only_active')
            ->where('quiz_exam_id', $this->examResult->quiz_exam_id)
            ->get();

        $this->form
            ->fill([
                'examQuestion' => $this->examQuestion,
            ]);

    }

    public function getTitle(): string
    {
        return __('Kết quả của') . " {$this->user->name}";
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema($this->getFormSchema())
                    ->columns(4),
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            // exam info
            $this->createExamInfoSection(),

            // results
            $this->createExamResultSection(),

        ];
    }

    private function createExamInfoSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make($this->getExamLabel())
            ->schema([
                Forms\Components\Grid::make('examResult')
                    ->schema([

                        TextInput::make('name')
                            ->disabled()
                            ->label('Tên:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->exam->name),

                        TextInput::make('code')
                            ->disabled()
                            ->label('Mã:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->exam->code),

                        TextInput::make('point')
                            ->disabled()
                            ->label('Điểm:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->examResult->point . "/10"),

                        TextInput::make('total_time')
                            ->disabled()
                            ->label('Tổng thời gian:')
                            ->translateLabel()
                            ->placeholder($this->formatTotalTime($this->examResult->total_time)),

                        TextInput::make('start_date')
                            ->disabled()
                            ->label('Ngày bắt đầu:')
                            ->translateLabel()
                            ->placeholder(fn() => Carbon::make($this->exam->start_date)->format('d-m-Y')),

                        TextInput::make('start_date')
                            ->disabled()
                            ->label('Ngày kết thúc:')
                            ->translateLabel()
                            ->placeholder(fn() => Carbon::make($this->exam->end_date)->format('d-m-Y')),

                    ])
                    ->columns(1),

            ])
            ->columnSpan(['lg' => 1]);
    }

    private function createExamResultSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Chi tiết bài thi')
            ->schema([
                Forms\Components\Repeater::make('examQuestion')
                    ->schema([
                        Forms\Components\Section::make(fn($get) => $this->getQuestionTitle($get('id')))
                            ->schema([

                                CheckboxList::make('exam_answers_only_active')
                                    ->options(fn($get) => $this->formatAnswers($get('exam_answers_only_active')))
                                    ->type(fn($get) => $this->getTypeOfAnswers($get('id')))
                                    ->disabled()
                                    ->hiddenLabel()

                            ])
                            ->description(fn($get) => $this->getQuestionStatistics($get('id')))
                            ->collapsed(false)
                            ->label(fn($get) => $this->getQuestionLabel($get('id'))),

                    ])
                    ->reorderableWithDragAndDrop(false)
                    ->deletable(false)
                    ->addable(false)
                    ->hiddenLabel(),

            ])
            ->columnSpan(['lg' => 3])
            ->translateLabel();
    }

    private function getExamLabel(): HtmlString
    {

        $imgSrc = asset($this->exam->image);

        $html = "<img src='{$imgSrc}' class='rounded-lg mr-4' alt='{$this->exam->name}'>";

        return new HtmlString($html);

    }

    private function formatTotalTime($totalTime): string|array
    {
        $interval = CarbonInterval::seconds($totalTime);

        // Định dạng đầu ra theo ngôn ngữ hiện tại của ứng dụng => In ra kết quả
        return $interval->cascade()->locale(app()->getLocale())->forHumans();
    }

    private function formatAnswers($answers): array
    {
        return collect($answers)->sortBy('order')->pluck('content', 'id')->toArray();
    }

    private function getAnsweredIds($questionsId)
    {
        return $this->examResult->results[$questionsId] ?? [];
    }

    private function getQuestionStatistics($questionsId): string|array
    {
        $answers = collect($this->examQuestion)->where('id', $questionsId)->first()['exam_answers_only_active'];

        $answeredIds = $this->getAnsweredIds($questionsId);

        $numberOfCorrectAnswers = collect($answers)->where('is_true', true)->count();

        $numberOfCorrectAnswersByUser = collect($answers)->whereIn('id', $answeredIds)->where('is_true', true)->count();

        $corrected = __('Chính xác');

        $incorrect = __('Không chính xác');

        if ($numberOfCorrectAnswers === 1) {

            $text = ($numberOfCorrectAnswersByUser === $numberOfCorrectAnswers) ? $corrected : $incorrect;

        } else {

            $text = "$corrected: {$numberOfCorrectAnswersByUser}/{$numberOfCorrectAnswers}";

        }

        return "[{$text}]";
    }

    private function getTypeOfAnswers($questionsId): string|array
    {
        $answers = collect($this->examQuestion)->where('id', $questionsId)->first()['exam_answers_only_active'];

        $answeredIds = $this->getAnsweredIds($questionsId);

        $output = [];

        foreach ($answers as $answer) {

            // checked or unchecked
            $checked = in_array($answer['id'], $answeredIds, true) ? 'checked' : 'unchecked';

            // true or false
            $checked .= $answer['is_true'] === 1 ? '-true' : '-false';

            $output[$answer['id']] = $checked;

        }

        return $output;
    }

    private function getQuestionTitle($questionId): HtmlString|array
    {
        $question = collect($this->examQuestion)->where('id', $questionId)->first();

        $html = "<span>{$question['title_origin']}</span>";

        if (!empty($question['image'])) {

            $imgSrc = asset($question['image']);

            $alt = $question['title_extra'] ?? $question['title_origin'];

            $html .= "
                    <div class='flex flex-col items-center my-2'>
                        <img src='{$imgSrc}' alt='{$alt}' class='mx-auto rounded-lg my-2 block' style='width: 30%;'>
                    ";

            if (!empty($question['title_extra'])) {

                $html .= "<span class='text-sm text-gray-500'>{$question['title_extra']}</span>";

            }

            $html .= "</div>";

        }

        return new HtmlString($html);
    }

}
