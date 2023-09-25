<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    public function majors()
    {
        return $this->belongsToMany(Major::class, 'campus_majors');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Campus $campus) {
            $campus->majors()->attach(Major::query()->pluck('id'));
        });
    }
}
