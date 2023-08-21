<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ContestResultController extends Controller
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

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => [
                    'required',
                    "exists:users,id,id,{$request->user_id}"
                ],
                'quiz_contest_id' => [
                    'required',
                    "exists:quiz_contests,id,id,{$request->quiz_contest_id}"
                ],
                'results' => 'required|array',
                'results.*' => 'required|array',
                'point' => 'required|numeric',
                'total_time' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $this->contestResult->query()->create($request->all());

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
