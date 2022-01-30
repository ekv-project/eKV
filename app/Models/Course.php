<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $fillable = [
        'code',
        'name',
    ];

    // Relationships

    public function courseGrades()
    {
        return $this->hasMany(CourseGrade::class);
    }
}
