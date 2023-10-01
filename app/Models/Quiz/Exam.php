<?php

namespace App\Models\Quiz;

use App\Models\Config\Major;
use App\Models\User;
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

    public function exam_questions()
    {
        return $this->hasMany(ExamQuestion::class, 'quiz_exam_id');
    }

    public function exam_questions_only_active()
    {
        return $this->hasMany(ExamQuestion::class, 'quiz_exam_id')->active();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Exam $exam) {

            $exam->major_id ??= auth()->user()->major_id;

            $exam->created_by ??= auth()->user()->id;

        });
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

}
