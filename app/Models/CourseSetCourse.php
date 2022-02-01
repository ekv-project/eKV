<?php

namespace App\Models;

use App\Models\Course;
use App\Models\CourseSet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSetCourse extends Model
{
    use HasFactory;
    protected $table = 'course_set_courses';
    protected $fillable = [
        'course_sets_id',
        'courses_code',
    ];

    // Relationships
    public function courseSet()
    {
        return $this->belongsTo(CourseSet::class, 'course_sets_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_code');
    }
}
