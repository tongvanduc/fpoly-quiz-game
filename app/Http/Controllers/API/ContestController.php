<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestResult;
use Illuminate\Http\Response;

class ContestController extends Controller
{
    /**
     * @param Contest $contest
     * @return void
     */
    public function __construct(
        public Contest $contest,
        public ContestResult $contestResult,
    )
    {

    }

    public function getListByUserID($userID)
    {
        try {
            $contestIDs = $this->contestResult->query()
                ->select('quiz_contest_id')
                ->distinct()
                ->where('user_id', $userID)
                ->pluck('quiz_contest_id')->toArray();

            $contests = $this->contest->query()->whereIn('id', $contestIDs)->get();

            return \response()->json([
                'contests' => $contests
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getInfoByCode($code)
    {
        try {
            $contest = $this->contest->query()->where('code', $code)->firstOrFail();

            return \response()->json([
                'contest' => $contest,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getResultByCode($code)
    {
        try {
            $contest = $this->contest->query()
                ->with([
                    'contest_result',
                    'contest_result.user',
                    'contest_questions',
                    'contest_questions.contest_answers'
                ])
                ->where('code', $code)
                ->firstOrFail();

            return \response()->json([
                'contest' => $contest
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
