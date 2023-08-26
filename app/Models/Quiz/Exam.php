<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'quiz_exams';

    public function exam_results()
    {
        return $this->hasMany(ExamResult::class, 'quiz_exam_id');
    }

//    public function exam_questions()
//    {
//        return $this->hasMany(ExamQuestion::class, 'quiz_exam_id');
//    }

    public function exam_questions_only_active()
    {
        return $this->hasMany(ExamQuestion::class, 'quiz_exam_id')->active();
    }

    public function exam_question()
    {
        return $this->belongsToMany(ExamQuestion::class, 'exams_has_questions','quiz_exam_id', 'quiz_exam_question_id');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
