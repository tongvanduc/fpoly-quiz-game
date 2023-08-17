<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_questions';

    protected $casts = [
        'correct_answers' => 'array'
    ];
}
