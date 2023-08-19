<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $table = 'quiz_contests';

    public function contest_result()
    {
        return $this->hasOne(ContestResult::class, 'quiz_contest_id');
    }

    public function contest_questions()
    {
        return $this->hasMany(ContestQuestion::class, 'quiz_contest_id');
    }
}
