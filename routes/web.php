<?php

use App\Http\Livewire\Form;
use Illuminate\Support\Facades\Route;

Route::get('form', Form::class);

Route::get('load-test', fn() => \App\Models\Quiz\Exam::query()->get());
