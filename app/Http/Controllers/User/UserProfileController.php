<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserProfileController extends Controller
{
    public function view($username){
        if(Gate::allows('authUser', $username)){
            return view('dashboard.user.profile.view')->with(['page' => 'Profil Pengguna', 'permission' => true]);
        }else{
            return view('dashboard.user.profile.view')->with(['page' => 'Profil Pengguna', 'permission' => false]);
        }
    }
    // Only the current authenticated user can update their own profile
    public function update($username){
        if(Gate::allows('authUser', $username)){
            return view('dashboard.user.profile.update')->with(['page' => 'Kemas Kini Profil Pengguna', 'permission' => true]);
        }else{
            return view('dashboard.user.profile.update')->with(['page' => 'Kemas Kini Profil Pengguna', 'permission' => false]);
        }
    }
    // Only current authenticated user (their own profile), admin and their coordinator is allowed to download the profile.
    public function download($username){
        if(Gate::allows('authUser', $username)){
            
        }elseif(Gate::allows('authAdmin', $username)){

        }elseif(Gate::allows('authCoordinator', $username)){

        }
    }
}
