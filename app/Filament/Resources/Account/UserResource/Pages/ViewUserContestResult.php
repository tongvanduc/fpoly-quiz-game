<?php

namespace App\Filament\Resources\Account\UserResource\Pages;

use App\Filament\Custom\Forms\Components\CheckboxList;
use App\Filament\Resources\Account\UserResource;
use App\Models\Quiz\ContestQuestion;
use App\Models\Quiz\ContestResult;
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

class ViewUserContestResult extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.account.user-resource.pages.view-user-contest-result';

    protected ?ContestResult $contestResult;

    public $contestQuestion;

    protected ?User $user;

    protected int $counter;

    public $contest;

    public function __construct()
    {
        $this->counter = 0;
    }

    public function mount($record, $related): void
    {
        $this->user = User::query()->findOrFail($record);

        $this->contestResult = ContestResult::query()
            ->where('user_id', $this->user->id)
            ->findOrFail($related);

        $this->contest = $this->contestResult->contest;

        $this->contestQuestion = ContestQuestion::with('contest_answers_only_active')
            ->where('quiz_contest_id', $this->contestResult->quiz_contest_id)
            ->get();

        $this->form
            ->fill([
                'contestQuestion' => $this->contestQuestion,
            ]);

    }

    public function getTitle(): string
    {
        return __('Result of') . " {$this->user->name}";
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
            // contest info
            $this->createContestInfoSection(),

            // results
            $this->createContestResultSection(),

        ];
    }

    private function createContestInfoSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make($this->getContestLabel())
            ->schema([
                Forms\Components\Grid::make('contestResult')
                    ->schema([

                        TextInput::make('name')
                            ->disabled()
                            ->label('Name:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->contest->name),

                        TextInput::make('code')
                            ->disabled()
                            ->label('Code:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->contest->code),

                        TextInput::make('point')
                            ->disabled()
                            ->label('Point:')
                            ->translateLabel()
                            ->placeholder(fn() => $this->contestResult->point . "/10"),

                        TextInput::make('total_time')
                            ->disabled()
                            ->label('Total time:')
                            ->translateLabel()
                            ->placeholder($this->formatTotalTime($this->contestResult->total_time)),

                        TextInput::make('start_date')
                            ->disabled()
                            ->label('Start date:')
                            ->translateLabel()
                            ->placeholder(fn() => Carbon::make($this->contest->start_date)->format('d-m-Y')),

                        TextInput::make('start_date')
                            ->disabled()
                            ->label('End date:')
                            ->translateLabel()
                            ->placeholder(fn() => Carbon::make($this->contest->end_date)->format('d-m-Y')),

                    ])
                    ->columns(1),

            ])
            ->columnSpan(['lg' => 1]);
    }

    private function createContestResultSection(): Forms\Components\Section
    {
        return Forms\Components\Section::make('Contest Result Detail')
            ->schema([
                Forms\Components\Repeater::make('contestQuestion')
                    ->schema([
                        Forms\Components\Section::make(fn($get) => $this->getQuestionTitle($get('id')))
                            ->schema([

                                CheckboxList::make('contest_answers_only_active')
                                    ->options(fn($get) => $this->formatAnswers($get('contest_answers_only_active')))
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

    private function getContestLabel(): HtmlString
    {

        $imgSrc = asset($this->contest->image);

        $html = "<img src='{$imgSrc}' class='rounded-lg mr-4' alt='{$this->contest->name}'>";

        return new HtmlString($html);

    }

    private function formatTotalTime($totalTime): string|array
    {
        try {

            $interval = CarbonInterval::seconds($totalTime);

            // Định dạng đầu ra theo ngôn ngữ hiện tại của ứng dụng
            // In ra kết quả
            return $interval->cascade()->locale(app()->getLocale())->forHumans();

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

    private function formatAnswers($answers): array
    {
        try {

            return collect($answers)->sortBy('order')->pluck('content', 'id')->toArray();

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

    private function getAnsweredIds($questionsId)
    {
        try {

            return $this->contestResult->results[$questionsId];

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

    private function getQuestionStatistics($questionsId): string|array
    {
        try {

            $answers = collect($this->contestQuestion)->where('id', $questionsId)->first()['contest_answers_only_active'];

            $answeredIds = $this->getAnsweredIds($questionsId);

            $numberOfCorrectAnswers = collect($answers)->where('is_true', true)->count();

            $numberOfCorrectAnswersByUser = collect($answers)->whereIn('id', $answeredIds)->where('is_true', true)->count();

            $corrected = __('Corrected');

            $incorrect = __('Incorrect');

            if ($numberOfCorrectAnswers === 1) {

                $text = ($numberOfCorrectAnswersByUser === $numberOfCorrectAnswers) ? $corrected : $incorrect;

            } else {

                $text = "$corrected: {$numberOfCorrectAnswersByUser}/{$numberOfCorrectAnswers}";

            }

            return "[{$text}]";

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

    private function getTypeOfAnswers($questionsId): string|array
    {
        try {

            $answers = collect($this->contestQuestion)->where('id', $questionsId)->first()['contest_answers_only_active'];

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

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

    private function getQuestionTitle($questionId): HtmlString|array
    {
        try {

            $question = collect($this->contestQuestion)->where('id', $questionId)->first();

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

        } catch (\Exception $e) {

            return data_when_error($e);

        }
    }

}
