<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = "code";
    public $incrementing = false;
    protected $fillable = [
        'code',
        'name'
    ];
}
