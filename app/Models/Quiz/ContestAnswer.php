<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_answers';

    public function contest_questions()
    {
        return $this->belongsTo(ContestQuestion::class, 'quiz_contest_question_id');
    }
}
