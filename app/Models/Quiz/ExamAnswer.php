<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_exam_answers';

    public function exam_questions()
    {
        return $this->belongsTo(ExamAnswer::class, 'quiz_exam_question_id');
    }

    public function exam_questions_only_active()
    {
        return $this->belongsTo(ExamAnswer::class, 'quiz_exam_question_id')->active();
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
