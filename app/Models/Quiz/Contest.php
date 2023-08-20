<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
