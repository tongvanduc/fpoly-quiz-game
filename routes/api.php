<?php

use App\Http\Controllers\API\AuthenController;
use App\Http\Controllers\API\ContestController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
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

        Route::prefix('contests')
            ->group(function () {
                Route::get('get-info/{code}', [ContestController::class, 'getInfoByCode']);
                Route::get('get-result/{code}', [ContestController::class, 'getResultByCode']);
                Route::get('get-list-by-user-id/{userID}', [ContestController::class, 'getListByUserID']);
            });
    });
