<?php

namespace App\Models;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassroomCoordinator extends Model
{
    use HasFactory;
    protected $table = 'classroom_coordinators';
    protected $fillable = [
        'users_username',
        'classrooms_id'
    ];
    // Relationships
    public function user(){
        return $this->belongsTo(User::class, 'users_username');
    }
    public function classroom(){
        return $this->belongsTo(Classroom::class, 'classrooms_id');
    }
}

