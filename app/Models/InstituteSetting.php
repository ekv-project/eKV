<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstituteSetting extends Model
{
    use HasFactory;
    protected $table = 'institute_settings';
    protected $fillable = [
        'institute_name',
        'institute_address',
        'institute_email_address',
        'institute_phone_number',
        'institute_fax',
    ];
}
