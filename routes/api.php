<?php

use App\Http\Controllers\API\AuthenController;
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

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
