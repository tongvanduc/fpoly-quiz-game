<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\Quiz\ExamResult;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ExamController extends Controller
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

    public function getInfoByCode($code)
    {
        try {
            $exam = $this->exam->query()
                ->active()
                ->with([
                    'exam_questions_only_active',
                    'exam_questions_only_active.exam_answers_only_active'
                ])
                ->where('code', $code)->firstOrFail();

            $user = request()->user();

            // Call sang API để kích hoạt realtime live-score
            // Phần call này để thông báo vừa mới có SV tham gia Quiz
            Http::post(env('ENDPOINT_LIVE_SCORE'), [
                'flag' => 'init',
                'code' => $code,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => random_avatar($user->name),
                ]
            ]);

            return \response()->json([
                'exam' => $exam,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getQuestionCount($code)
    {
        try {
            $exam = $this->exam->query()
                ->active()
                ->with(['exam_questions_only_active',])
                ->where('code', $code)->firstOrFail();

            return \response()->json([
                'question_count' => $exam->exam_questions_only_active()->count(),
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getResultByCode($code, $userID = null)
    {
        try {
            $exam = $this->exam->query()
                ->active()
                ->with([
                    'exam_results' => function ($query) use ($userID) {
                        $query->when($userID, function ($query2) use ($userID) {
                            $query2->where('user_id', $userID);
                        });
                    },
                    'exam_results.user',
                    'exam_questions_only_active',
                    'exam_questions_only_active.exam_answers_only_active'
                ])
                ->where('code', $code)
                ->firstOrFail();

            return \response()->json([
                'exam' => $exam
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getListByUserID($userID)
    {
        try {
            $user = $this->user->query()->findOrFail($userID);

            $examIDs = $this->examResult->query()
                ->select('quiz_exam_id')
                ->distinct()
                ->where('user_id', $user->id)
                ->pluck('quiz_exam_id')->toArray();

            $exams = $this->exam->query()
                ->with([
                    'exam_results' => function ($query) use ($userID) {
                        $query->when($userID, function ($query2) use ($userID) {
                            $query2->where('user_id', $userID);
                        });
                    },
                ])
                ->active()
                ->whereIn('id', $examIDs)
                ->get();

            return \response()->json([
                'exams' => $exams
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
