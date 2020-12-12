<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table = 'programs';
    protected $primaryKey = "code";
    public $incrementing = false;
    protected $fillable = [
        'code',
        'name'
    ];
}
