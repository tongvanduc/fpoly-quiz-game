<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_results';

    protected $casts = [
        'results' => 'array'
    ];
}
