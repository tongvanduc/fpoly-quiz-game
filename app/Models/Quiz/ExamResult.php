<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_exam_results';

    protected $casts = [
        'results' => 'array'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'quiz_exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
