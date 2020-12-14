<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserProfileController extends Controller
{
    public function view($username){
        if(Gate::allows('authUser', $username) || Gate::allows('authAdmin', $username) || Gate::allows('authSuperAdmin', $username) || Gate::allows('authCoordinator', $username)){
            return view('dashboard.user.profile.view')->with(['page' => 'Profil Pengguna', 'username' => $username]);
        }
    }
    // Only the current authenticated user can view their own profile update page
    public function updateView($username){
        if(Gate::allows('authUser', $username)){
            return view('dashboard.user.profile.update')->with(['page' => 'Kemas Kini Profil Pengguna', 'username' => $username]);
        }
    }
    // Only the current authenticated user can update their profile
    public function update(Request $request, $username){
        if(Gate::allows('authUser', $username)){
            $validated = $request->validate([
                'identification_number' => ['required'],
                'phone_number' => ['required'],
                'date_of_birth' => ['required'],
                'place_of_birth' => ['required'],
                'home_address' => ['required'],
                'home_number' => ['required'],
                'guardian_name' => ['required'],
                'guardian_phone_number' => ['required']
            ]);
            if(UserProfile::where('users_username', $request->username)->first()){
                // Update user profile if user profile is exist.
                UserProfile::where('users_username', $request->username)->update([
                    'identification_number' => $request->identification_number,
                    'phone_number' => $request->phone_number,
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'home_address' => $request->home_address,
                    'home_number' => $request->home_number,
                    'guardian_name' => $request->guardian_name,
                    'guardian_phone_number' => $request->guardian_phone_number
                ]);
                session()->flash('profileUpdateSuccess', 'Profil berjaya dikemas kini!');
                return redirect()->route('profile.user_update', $username);
            }elseif(!UserProfile::where('users_username', $request->username)->first()){
                // Create new profile if user profile not exist.
                UserProfile::create([
                    'users_username' => $username,
                    'identification_number' => $request->identification_number,
                    'phone_number' => $request->phone_number,
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'home_address' => $request->home_address,
                    'home_number' => $request->home_number,
                    'guardian_name' => $request->guardian_name,
                    'guardian_phone_number' => $request->guardian_phone_number
                ]);
                session()->flash('profileUpdateSuccess', 'Profil berjaya dikemas kini!');
                return redirect()->route('profile.user_update', $username);
            }
        }
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
