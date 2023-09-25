<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status'
    ];

    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'campus_majors');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Major $major) {
            $major->campuses()->attach(Campus::query()->pluck('id'));
        });
    }

}
