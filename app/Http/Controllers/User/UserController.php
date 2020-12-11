<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'username' => 'Pengguna tidak wujud.',
            'password' => 'Kata Laluan Salah.'
        ]);
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Add new user/users.
     * 
     * Either manually one by one (in progress)
     * or bulk add (no progress)
     * 
     */
    public function addNewUser(Request $request){
        // Check if user already exist
        if(User::where('username', $request->username)->first()){
            return back()->withErrors([
                'userExist' => 'Pengguna telah wujud!',
            ]);
        }else{
        // If no user exist, add them
            $validated = $request->validate([
                'fullname' => ['required'],
                'username' => ['required', 'min:8'],
                'email' => ['required', 'email:rfc,dns'],
                'password' => ['required', 'confirmed'],
                'role' => ['required']
            ]);
            // If validation failed, display the error
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            redirect()->route('admin.user_add');
        }
    }
}
