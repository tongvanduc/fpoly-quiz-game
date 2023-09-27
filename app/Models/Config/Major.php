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
        return $this->belongsTo(Campus::class);
    }

}
