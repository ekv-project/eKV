<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginController extends MainController
{
    public function rootPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('login')->with(['page' => 'Log Masuk', 'settings' => $this->instituteSettings]);
        }
    }
}
