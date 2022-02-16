<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = 'user_profiles';
    protected $fillable = [
        'users_username',
        'phone_number',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'home_address',
        'home_number',
        'guardian_name',
        'guardian_phone_number',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
