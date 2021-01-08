<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterGrade extends Model
{
    use HasFactory;
    protected $table = 'course_grades';
    protected $fillable = [
        'users_username',
        'study_levels_code',
        'semester',
        'total_credit_gpa',
        'total_credit_cgpa',
        'gpa',
        'cgpa'
    ];
}
