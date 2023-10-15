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
