<?php

namespace App\Http\Controllers;

use App\Models\PerformanceTesting;
use Illuminate\Http\Request;

class PerformanceTestingController extends Controller
{
    public function index()
    {
        return PerformanceTesting::all();
    }

    public function store()
    {
        return PerformanceTesting::query()->create([
            'field_1' => fake()->text(),
            'field_2' => fake()->text(),
            'field_3' => fake()->text(),
            'field_4' => fake()->text(),
            'field_5' => fake()->text(),
            'field_6' => fake()->text(),
            'field_7' => fake()->text(),
            'field_8' => fake()->text(),
            'field_9' => fake()->text(),
            'field_10' => fake()->text(),
            'field_11' => fake()->text(),
            'field_12' => fake()->text(),
            'field_13' => fake()->text(),
            'field_14' => fake()->text(),
            'field_15' => fake()->text(),
            'field_16' => fake()->text(),
            'field_17' => fake()->text(),
            'field_18' => fake()->text(),
            'field_19' => fake()->text(),
            'field_20' => fake()->text(),
        ]);
    }
}
