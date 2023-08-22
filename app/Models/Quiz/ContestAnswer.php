<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_answers';

    public function contest_questions()
    {
        return $this->belongsTo(ContestAnswer::class, 'quiz_contest_question_id');
    }

    public function contest_questions_only_active()
    {
        return $this->belongsTo(ContestAnswer::class, 'quiz_contest_question_id')->active();
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
