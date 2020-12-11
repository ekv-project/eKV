<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = 'user_profiles';
    protected $fillable = [
        'users_username',
        'date_of_birth',
        'place_of_birth',
        'home_address',
        'phone_number',
        'home_number',
        'guardian_name',
        'guardian_phone_number',
        'classrooms_id'
    ];
}
