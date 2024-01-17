<?php

use App\Http\Controllers\PerformanceTestingController;
use App\Http\Livewire\Form;
use Illuminate\Support\Facades\Route;

Route::get('form', Form::class);

Route::prefix('performance-testing')
    ->group(function () {
        Route::get('index', [PerformanceTestingController::class, 'index']);
        Route::get('store', [PerformanceTestingController::class, 'store']);
    });

Route::get('live-results', function () {
    return view('live-results');
});

Route::get('live-score', function () {
    $randomScore = random_int(1, 4);

//    dd(\App\Models\TmpQuizResult::query()->find(2));

    \App\Models\TmpQuizResult::query()->find(2)->update(['point' => $randomScore]);

    $newClass = new stdClass();
    $newClass->code = "9YBJFBB0";
    $newClass->flag = "update";
    $data = [
//        'id' => 11,
        'name' => 'Nguyễn Văn A',
        'total_point' => $randomScore,
        'point' => $randomScore,
        'total_time' => 5,
    ];

    \event(new \App\Events\ResultLiveScoreEvent(request(), $data));

    return 'success';
});
