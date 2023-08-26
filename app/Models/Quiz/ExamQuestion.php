<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_exam_questions';

    public function exam_answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'quiz_exam_question_id');
    }

    public function exam_answers_only_active(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'quiz_exam_question_id')->active();
    }

    public function exams(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'quiz_exam_id');
    }

    public function exams_only_active(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'quiz_exam_id')->active();
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
