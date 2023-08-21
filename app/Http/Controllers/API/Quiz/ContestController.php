<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestResult;
use App\Models\User;
use Illuminate\Http\Response;

class ContestController extends Controller
{
    /**
     * @param Contest $contest
     * @return void
     */
    public function __construct(
        public User          $user,
        public Contest       $contest,
        public ContestResult $contestResult,
    )
    {

    }

    public function getInfoByCode($code)
    {
        try {
            $contest = $this->contest->query()
                ->active()
                ->with([
                    'contest_questions_only_active',
                    'contest_questions_only_active.contest_answers_only_active'
                ])
                ->where('code', $code)->firstOrFail();

            return \response()->json([
                'contest' => $contest,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getResultByCode($code, $userID = null)
    {
        try {
            $contest = $this->contest->query()
                ->active()
                ->with([
                    'contest_results' => function ($query) use ($userID) {
                        $query->when($userID, function ($query2) use ($userID) {
                            $query2->where('user_id', $userID);
                        });
                    },
                    'contest_results.user',
                    'contest_questions_only_active',
                    'contest_questions_only_active.contest_answers_only_active'
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

    public function getListByUserID($userID)
    {
        try {
            $user = $this->user->query()->findOrFail($userID);

            $contestIDs = $this->contestResult->query()
                ->select('quiz_contest_id')
                ->distinct()
                ->where('user_id', $user->id)
                ->pluck('quiz_contest_id')->toArray();

            $contests = $this->contest->query()
                ->with([
                    'contest_results' => function ($query) use ($userID) {
                        $query->when($userID, function ($query2) use ($userID) {
                            $query2->where('user_id', $userID);
                        });
                    },
                ])
                ->active()
                ->whereIn('id', $contestIDs)
                ->get();

            return \response()->json([
                'contests' => $contests
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
