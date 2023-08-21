<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContestQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_contest_questions';

    public function contest_answers(): HasMany
    {
        return $this->hasMany(ContestAnswer::class,'quiz_contest_question_id')->active();
    }

    //dung belongs to lay ra list contests
    public function contests(): BelongsTo
    {
        return $this->belongsTo(Contest::class, 'quiz_contest_id')->active();
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
