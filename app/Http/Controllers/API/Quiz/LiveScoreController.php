<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\TmpQuizResult;
use Illuminate\Http\Response;

class LiveScoreController extends Controller
{
    /**
     * @param Exam $exam
     * @return void
     */
    public function __construct(
        public Exam          $exam,
        public TmpQuizResult $tmpQuizResult,
    )
    {

    }

    public function refesh($code)
    {
        try {
            $this->exam->query()->active()->where('code', $code)->firstOrFail();

            // Xử lý tổng hợp dữ liệu điểm trả cho API
            $data = $this->tmpQuizResult->query()
                ->selectRaw('tmp_quiz_results.user_id, users.name, SUM(time) as total_time, SUM(point) as total_point')
                ->join('users', 'users.id', '=', 'tmp_quiz_results.user_id')
                ->where('tmp_quiz_results.code', $code)
                ->groupBy('tmp_quiz_results.user_id')
                ->orderByDesc('total_point')
                ->orderBy('total_time')
                ->get();

            return \response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
