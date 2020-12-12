<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;
    protected $table = 'login_activities';
    protected $fillable = [
        'users_username',
        'ip_address',
        'user_agent'
    ];
}
