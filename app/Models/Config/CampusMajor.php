<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusMajor extends Model
{
    use HasFactory;

    protected $table = 'campus_majors';

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
