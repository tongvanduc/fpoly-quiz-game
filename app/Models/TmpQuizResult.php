<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpQuizResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_exam_id',
        'quiz_exam_question_id',
        'code',
        'answers',
        'point',
        'time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'answers' => 'array'
    ];
}
