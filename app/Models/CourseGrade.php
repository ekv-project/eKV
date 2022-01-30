<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseGrade extends Model
{
    use HasFactory;
    protected $table = 'course_grades';
    protected $fillable = [
        'users_username',
        'courses_code',
        'study_levels_code',
        'semester',
        'credit_hour',
        'grade_pointer',
    ];

    // Relationships
    public function user()
    {
        return $this->hasOne(User::class, 'users_username');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_code');
    }

    public function studyLevel()
    {
        return $this->hasOne(StudyLevel::class, 'code');
    }
}
