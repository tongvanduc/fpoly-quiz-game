<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamQuestion;
use App\Models\TmpQuizResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * @param Exam $exam
     * @return void
     */
    public function __construct(
        public User         $user,
        public Exam         $exam,
        public ExamQuestion $examQuestion,
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
                'answers' => 'required|array',
                'answers.*' => 'required|numeric',
                'point' => 'required|numeric',
                'time' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $data['quiz_exam_id'] = $exam->id;
            // Lưu thông tin kết quả của câu hỏi hiện tại
            TmpQuizResult::query()->create($data);

            // Xử lý tổng hợp dữ liệu điểm trả cho API
            $data = TmpQuizResult::query()
                ->selectRaw('tmp_quiz_results.user_id, users.name, SUM(time) as total_time, SUM(point) as total_point')
                ->join('users', 'users on users.id', '=', 'tmp_quiz_results.user_id')
                ->where('code', $code)
                ->groupBy('user_id')
                ->get();

            // Call sang API để kích hoạt realtime live-score

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
