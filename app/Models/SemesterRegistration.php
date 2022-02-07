<?php

namespace App\Models;

use App\Models\User;
use App\Models\SemesterSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SemesterRegistration extends Model
{
    use HasFactory;
    protected $table = 'semester_registrations';
    protected $fillable = [
        'semester_sessions_id',
        'users_username',
    ];

    // Relationships
    public function semesterSession()
    {
        return $this->hasOne(SemesterSession::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
