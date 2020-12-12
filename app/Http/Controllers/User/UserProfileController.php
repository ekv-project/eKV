<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserProfileController extends Controller
{
    public function view($username){
        return view('dashboard.user.profile.view')->with(['page' => 'Profil Pengguna', 'username' => $username]);
    }
    // Only the current authenticated user can update their own profile
    public function update($username){
        return view('dashboard.user.profile.update')->with(['page' => 'Kemas Kini Profil Pengguna', 'username' => $username]);
    }
    // Only current authenticated user (their own profile), admin and their coordinator is allowed to download the profile.
    public function download($username){
        if(Gate::allows('authUser', $username)){
            dd("user");
        }elseif(Gate::allows('authAdmin', $username)){
            dd("admin");
        }elseif(Gate::allows('authCoordinator', $username)){
            dd("coordinator");
        }
    }
}
