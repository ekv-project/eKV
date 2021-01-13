<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterGrade extends Model
{
    use HasFactory;
    protected $table = 'semester_grades';
    protected $fillable = [
        'users_username',
        'study_levels_code',
        'semester',
        'total_credit_gpa',
        'total_credit_cgpa',
        'gpa',
        'cgpa'
    ];
    // Relationships
    public function user(){
        return $this->hasOne(User::class, 'users_username');
    }
    public function course(){
        return $this->hasOne(Course::class, 'code');
    }
    public function studyLevel(){
        return $this->hasOne(StudyLevel::class, 'code');
    }
}
