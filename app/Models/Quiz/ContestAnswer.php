<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContestAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_answers';

    public function contest_questions()
    {
        return $this->belongsTo(ContestAnswer::class, 'quiz_contest_question_id');
    }
}
