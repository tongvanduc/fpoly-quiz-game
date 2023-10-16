<?php

use App\Http\Controllers\API\AuthenController;
use App\Http\Controllers\API\Quiz\ExamController;
use App\Http\Controllers\API\Quiz\ExamResultController;
use App\Http\Controllers\API\Quiz\LiveScoreController;
use App\Http\Controllers\API\Quiz\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthenController::class, 'register']);
Route::post('login', [AuthenController::class, 'login']);

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::post('logout', [AuthenController::class, 'logout']);

        Route::prefix('exams')
            ->group(function () {
                Route::get('get-question-count/{code}', [ExamController::class, 'getQuestionCount']);
                Route::get('get-info/{code}', [ExamController::class, 'getInfoByCode']);
                Route::get('get-result/{code}/{userID?}', [ExamController::class, 'getResultByCode']);
                Route::get('get-list-by-user-id/{userID}', [ExamController::class, 'getListByUserID']);
            });

        Route::prefix('exam_results')
            ->group(function () {
                Route::post('/', [ExamResultController::class, 'store']);
            });

        Route::prefix('tmp')
            ->group(function () {
                Route::post('quiz-result-next-question/{code}', [QuestionController::class, 'nextQuestion']);
            });
    });

// Không cần đăng nhập cũng xem được thông qua Exam Code
Route::prefix('exams')
    ->group(function () {
        Route::get('get-question-count/{code}', [ExamController::class, 'getQuestionCount']);
    });

Route::prefix('live_score')
    ->group(function () {
        Route::get('refesh/{code}', [LiveScoreController::class, 'refesh']);
    });
