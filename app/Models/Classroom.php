<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;
    protected $table = 'classrooms';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'programs_code',
        'admission_year',
        'study_year',
        'study_levels_code',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(ClassroomStudent::class, 'classrooms_id');
    }

    public function coordinator()
    {
        return $this->hasOne(ClassroomCoordinator::class, 'classrooms_id');
    }

    public function program()
    {
        return $this->hasOne(Program::class, 'code');
    }
}
