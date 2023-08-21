<?php

namespace App\Http\Controllers\API\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Quiz\StoreContestResultRequest;
use App\Models\Quiz\Contest;
use App\Models\Quiz\ContestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function store(StoreContestResultRequest $request)
    {
        try {
            $this->contestResult->query()->create($request->all());

            return \response()->json([], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(data_when_error($exception), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
