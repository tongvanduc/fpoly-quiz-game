<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContestResult extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_results';

    protected $casts = [
        'results' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class, 'quiz_contest_id');
    }
}
