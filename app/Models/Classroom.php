<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    protected $fillable = [
        'programs_code',
        'admission_year',
        'coordinators_id',
        'study_year',
        'study_levels_code'
    ];
}
