<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnouncementPost;
use App\Http\Controllers\MainController;

class DashboardController extends MainController
{
    public function dashboard(Request $request){
        $pagination = 4;
        $announcementPosts = AnnouncementPost::join('users', 'announcement_posts.user_id', '=', 'users.id')->select('announcement_posts.id', 'announcement_posts.title', 'announcement_posts.created_at',  'users.fullname')->paginate($pagination)->withQueryString();
        return view('dashboard.dashboard')->with(['page' => 'Dashboard', 'announcementPosts' => $announcementPosts, 'settings' => $this->instituteSettings]);
    }
}
