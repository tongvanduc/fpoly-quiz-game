<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamQuestion;
use App\Models\TmpQuizResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * @param Exam $exam
     * @return void
     */
    public function __construct(
        public User          $user,
        public Exam          $exam,
        public ExamQuestion  $examQuestion,
        public TmpQuizResult $tmpQuizResult,
    )
    {

    }

    public function nextQuestion(Request $request, $code)
    {
        try {
            $exam = $this->exam->query()->active()->where('code', $code)->firstOrFail();

            $data = $request->all();

            $validator = Validator::make($data, [
                'user_id' => [
                    'required',
                    "exists:users,id,id,{$request->user_id}"
                ],
                'quiz_exam_question_id' => [
                    'required',
                    "exists:quiz_exam_questions,id,id,{$request->quiz_exam_question_id}"
                ],
                'answers' => 'array',
                'answers.*' => 'numeric',
                'point' => 'required|numeric',
                'time' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $data['quiz_exam_id'] = $exam->id;
            $data['code'] = $code;

            // Lưu thông tin kết quả của câu hỏi hiện tại
            $this->tmpQuizResult->query()->create($data);

            // Xử lý tổng hợp dữ liệu điểm trả cho API
            $data = $this->tmpQuizResult->query()
                ->selectRaw('tmp_quiz_results.user_id, users.name, SUM(time) as total_time, SUM(point) as total_point')
                ->join('users', 'users.id', '=', 'tmp_quiz_results.user_id')
                ->where('tmp_quiz_results.code', $code)
                ->groupBy('tmp_quiz_results.user_id')
                ->orderByDesc('total_point')
                ->get();

            // Call sang API để kích hoạt realtime live-score
            // Phần call này để thông báo sv đã thực hiện xong câu hỏi hiện tại
            // Màn hình Live core update bảng xếp hạng
            Http::post(env('ENDPOINT_LIVE_SCORE'), [
                'flag' => 'next-question',
                'code' => $code,
                'data' => $data->toArray()
            ]);

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
