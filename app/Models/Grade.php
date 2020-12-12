<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $table = 'grades';
    protected $fillable = [
        'users_username',
        'courses_code',
        'study_levels_code',
        'semester',
        'marks'
    ];
}
