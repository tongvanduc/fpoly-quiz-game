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

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
