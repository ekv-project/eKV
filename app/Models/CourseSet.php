<?php

namespace App\Models;

use App\Models\Program;
use App\Models\StudyLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSet extends Model
{
    use HasFactory;
    protected $table = 'course_sets';
    protected $fillable = [
        'study_levels_code',
        'programs_code',
        'semester',
    ];

    // Relationships
    public function studyLevel()
    {
        return $this->hasOne(StudyLevel::class, 'study_levels_code');
    }

    public function programs()
    {
        return $this->hasOne(Program::class, 'programs_code');
    }
}
