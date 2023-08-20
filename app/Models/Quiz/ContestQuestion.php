<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContestQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_questions';

    protected $casts = [
        'correct_answers' => 'array'
    ];

    public function contest_answers(): HasMany
    {
        return $this->hasMany(ContestAnswer::class,'id', 'quiz_contest_question_id');
    }

    //dung belongs to lay ra list contests
    public function contests(): BelongsTo
    {
        return $this->belongsTo(Contest::class, 'quiz_contest_id');
    }
}
