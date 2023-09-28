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
        'status',
        'campus_id',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    protected static function booted(): void
    {
        static::creating(function (Major $major) {
            if (empty($major->campus_id)) {
                $campus_id = Major::query()->findOrFail(auth()->user()->major_id)->campus_id;
                $major->campus_id = $campus_id;
            }
        });
    }
}
