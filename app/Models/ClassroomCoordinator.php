<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomCoordinator extends Model
{
    use HasFactory;
    protected $table = 'classroom_coordinators';
    protected $fillable = [
        'users_username',
        'classrooms_id'
    ];
    // Relationships
    public function classroom(){
        return $this->belongsTo(Classroom::class, 'classrooms_id');
    }
}

