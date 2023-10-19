<?php

namespace App\Filament\Resources\Quiz;

use App\Filament\Resources\Quiz\ExamResource\Pages;
use App\Filament\Widgets\ExamStats;
use App\Models\Config\Campus;
use App\Models\Config\Major;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamAnswer;
use App\Models\Quiz\ExamQuestion;
use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                                    ->placeholder('Tên bài thi')
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
                                            ->placeholder('Thời gian làm tối đa 1 câu. Đơn vị: giây')
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->required(),

                                        Forms\Components\TextInput::make('max_of_tries')
                                            ->gt(0)
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->placeholder('Số lần làm tối đa')
                                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Select::make('major_id')
                            ->label('Image')
                            ->visible(fn(): bool => is_super_admin())
                            ->options(Major::all()->pluck('name', 'id'))
                            ->searchable(),

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
                                    ->label('Trạng thái kích hoạt')
                                    ->helperText('Bài kiểm tra này sẽ bị ẩn khỏi tất cả các bài kiểm tra trong danh sách.')
                                    ->default(true),

                                Forms\Components\DateTimePicker::make('start_date')
                                    ->label('Start date')
                                    ->placeholder('Ngày bắt đầu')
                                    ->default(now())
                                    ->afterOrEqual(now()->format('d-m-Y H'))
                                    ->seconds(false)
                                    ->required(),

                                Forms\Components\DateTimePicker::make('end_date')
                                    ->label('End date')
                                    ->placeholder('Ngày kết thúc')
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
            ->modifyQueryUsing(fn(Builder $query) => is_super_admin() ? $query : $query->where('major_id', auth()->user()->major_id))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên bài thi')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->defaultImageUrl(asset('image/no-image-icon.png'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->date('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->date('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('timer')
                    ->label('Thời gian làm tối đa 1 câu')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_of_tries')
                    ->label('Số lần làm bài')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái kích hoạt')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('major.campus.name')
                    ->label('Cơ sở')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('major.name')
                    ->label('Ngành')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Tạo bởi ai?')
                    ->default('')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái kích hoạt')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('import')
                        ->label('Nhập câu hỏi')
                        ->icon('heroicon-m-cloud-arrow-up')
                        ->color('danger')
                        ->form([
                            Forms\Components\FileUpload::make('file')
                                ->label('Nhập câu hỏi bằng tệp excel (xlsx)')
                                ->required()
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ])
                        ->action(function (array $data, Exam $exam) {

                            $excelFile = Storage::path($data['file']);

                            [$code, $message] = array_values(self::importQuestions($excelFile, $exam->id));

                            $notiType = $code == 200 ? 'success' : 'danger';

                            Storage::delete($data['file']);

                            Notification::make()
                                ->title($message)
                                ->$notiType()
                                ->send();
                        }),

                    Tables\Actions\Action::make('detail')
                        ->label('Detail')
                        ->icon('heroicon-m-eye')
                        ->color('info')
                        ->url(function ($record) {
                            return route('filament.admin.resources.quiz.exams.exam_detail', ['record' => $record->id]);
                        }),

                    Tables\Actions\Action::make('question')
                        ->label('Câu hỏi')
                        ->icon('heroicon-m-eye')
                        ->color('success')
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
                        ->color('warning')
                        ->slideOver(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('5xl')
                    ->slideOver(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download-ex-import-file')
                    ->label('Tải tệp nhập câu hỏi mẫu')
                    ->action(function () {
                        return response()->download(public_path(EXAMPLE_QUESTIONS_IMPORT_FILE));
                    })
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
            'exam_detail' => Pages\ExamDetail::route('/{record}'),
        ];
    }

    private static function importQuestions($excelFile, $exam_id)
    {
        try {
            $spreadsheet = IOFactory::load($excelFile);

            $sheetCount = $spreadsheet->getSheetCount();

            // Lấy ra sheet chứa câu hỏi
            $questionsSheet = $spreadsheet->getSheet(0);

            $questionsArr = $questionsSheet->toArray();

            // Lấy ra sheet chứa ảnh
            $imagesSheet = $sheetCount > 1 ? $spreadsheet->getSheet(1) : null;

            [$questions, $answers, $imageCodeToQuestionId] = self::handleQuestionSheet($questionsArr, $exam_id);

            // Lấy ra các đối tượng Drawing trong sheet
            if ($imagesSheet) {

                // Chuyển sheet thành một mảng dữ liệu
                $sheetData = $imagesSheet->toArray();

                $drawings = $imagesSheet->getDrawingCollection();

                $imgArr = [];

                $imgMemArr = [];

                // Duyệt qua các đối tượng Drawing
                foreach ($drawings as $index => $drawing) {

                    // Kiểm tra xem đối tượng Drawing có phải là MemoryDrawing hay không
                    [$code, $others] = $sheetData[$index + 1];

                    if (empty($imageCodeToQuestionId[$code])) continue;

                    $questionId = $imageCodeToQuestionId[$code];

                    $filename = self::handleDrawingMimeType($drawing, $questionId, $imgArr, $imgMemArr);

                    $questions[$questionId]['image'] = $filename;
                }
            }
            DB::beginTransaction();

            ExamQuestion::query()->insert($questions);

            ExamAnswer::query()->insert($answers);

            self::uploadQuestionImage($imgArr, $imgMemArr);

            DB::commit();

            return [
                'code' => Response::HTTP_OK,
                'message' => 'Nhập câu hỏi thành công'
            ];

        } catch (Exception $e) {
            return data_when_error($e);
        }
    }

    private static function handleQuestionSheet($questionSheet, $exam_id)
    {

        $maxQuestionId = ExamQuestion::query()->max('id') ?? 0;

        $questions = [];

        $answers = [];

        $imageCodeToQuestionId = [];

        foreach ($questionSheet as $key => $row) {
            if ($key == 0) continue;

            $line = $key + 1;

            [$title_origin, $title_extra, $explain, $answer, $is_correct, $order, $imageCode] = $row;

            if ($title_origin != null || trim($title_origin) != "") {

                $questions[++$maxQuestionId] = [
                    'id' => $maxQuestionId,
                    'title_origin' => $title_origin ?? null,
                    'title_extra' => $title_extra ?? null,
                    'explain' => $explain ?? null,
                    'quiz_exam_id' => $exam_id,
                    'is_active' => 1,
                    'image' => $imageCode ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (!empty($imageCode)) $imageCodeToQuestionId[$imageCode] = $maxQuestionId;

                catchError($answer, "Thiếu câu trả lời trong dòng {$line}");

            }

            if (!empty($answer)) {
                $answers[] = [
                    'content' => $answer,
                    'is_true' => !empty($is_correct) ? 1 : 0,
                    'order' => $order ?: 0,
                    'is_active' => 1,
                    'quiz_exam_question_id' => $maxQuestionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

            }

        }

        return [
            $questions,
            $answers,
            $imageCodeToQuestionId,
        ];
    }

    private static function handleDrawingMimeType($drawing, $questionId, &$imgArr, &$imgMemArr)
    {
        if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {

            // Lấy ảnh từ phương thức getImageResource
            $image = $drawing->getImageResource();

            // Xác định định dạng của ảnh dựa vào phương thức getMimeType
            switch ($drawing->getMimeType()) {

                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG:
                    $format = "png";
                    break;

                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
                    $format = "gif";
                    break;

                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG:
                    $format = "jpg";
                    break;

            }

            // Tạo một tên file cho ảnh
            $filename = "image_question" . md5($questionId) . '_' . uniqid() . "." . $format;

            $imgMemArr[] = [
                'filename' => $filename,
                'image' => $image,
            ];

        } else {

            // Lấy ảnh từ phương thức getPath
            $path = $drawing->getPath();

            // Đọc nội dung của ảnh bằng cách sử dụng fopen và fread
            $file = fopen($path, "r");

            $content = "";

            while (!feof($file)) {
                $content .= fread($file, 1024);
            }

            // Lấy định dạng của ảnh từ phương thức getExtension
            $format = $drawing->getExtension();

            // Tạo một tên file cho ảnh
            $filename = "image_question" . md5($questionId) . '_' . uniqid() . "." . $format;

            $imgArr[] = [
                'filename' => $filename,
                'content' => $content
            ];
        }

        return $filename;
    }

    private static function uploadQuestionImage($imgArr, $imgMemArr)
    {
        if (!empty($imgArr)) {
            foreach ($imgArr as $imgCode => $item) {
                Storage::put($item['filename'], $item['content']);
            }
        }

        // Lưu ảnh
        if (!empty($imgMemArr)) {
            foreach ($imgMemArr as $item) {
                if (!empty($imageQuestionArr[$imgCode])) {
                    $tempPath = sys_get_temp_dir() . $item['filename'];

                    imagepng($item['image'], $tempPath);

                    $content = file_get_contents($tempPath);

                    Storage::put($item['filename'], $content);

                    unlink($tempPath);
                }
            }
        }
    }
}
