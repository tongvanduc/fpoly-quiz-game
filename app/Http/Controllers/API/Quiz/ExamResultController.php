<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ExamResultController extends Controller
{
    /**
     * @param Exam $exam
     * @return void
     */
    public function __construct(
        public User       $user,
        public Exam       $exam,
        public ExamResult $examResult,
    )
    {

    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => [
                    'required',
                    "exists:users,id,id,{$request->user_id}"
                ],
                'quiz_exam_id' => [
                    'required',
                    "exists:quiz_exams,id,id,{$request->quiz_exam_id}"
                ],
                'results' => 'array',
                'results.*' => 'array',
                'point' => 'required|numeric',
                'total_time' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $exam = $this->exam->query()->find($request->quiz_exam_id);
            $examResultCount = $this->examResult->query()
                ->where([
                    'user_id' => $request->user_id,
                    'quiz_exam_id' => $request->quiz_exam_id,
                ])
                ->count();

            if ($exam->max_of_tries < $examResultCount) {
                return response([
                    'max_of_tries' => [__('max_of_tries')]
                ], Response::HTTP_BAD_REQUEST);
            }

            $this->examResult->query()->create($request->all());

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
