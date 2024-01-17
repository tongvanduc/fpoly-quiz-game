<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Exam;
use App\Models\TmpQuizResult;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

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

    public function index(Request $request)
    {
        $data = Cache::get('exam.' . $request->code, []);

        $key = PREFIX_CACHE . $request->data['id'];

        $newUser = [
            'user_id' => $request->data['id'],
            'name' => $request->data['name'],
            'total_time' => $request->data['total_time'] ?? 0,
            'total_point' => $request->data['total_point'] ?? 0,
            'errors' => $request->data['errors'] ?? 0,
        ];

        $data[$key] = $newUser;

        Cache::put('exam.' . $request->code, $data, TTL_CACHE);

        \event(new \App\Events\ResultLiveScoreEvent($request, array_values($data)));

        return array_values($data);
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
