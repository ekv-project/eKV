<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnnouncementPost extends Model
{
    use HasFactory;
    protected $table = 'announcement_posts';
    protected $primaryKey = "id";
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'title',
        'content'
    ];

    // Relationships

    public function author(){
        return $this->belongsTo(User::class);
    }
}
