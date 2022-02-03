<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterSession extends Model
{
    use HasFactory;
    protected $table = 'semester_sessions';
    protected $fillable = [
        'course_sets_id',
        'session',
        'year',
        'status',
    ];

    // Relationships
    public function courseSet()
    {
        return $this->belongsTo(CourseSet::class, 'course_sets_id');
    }
}
