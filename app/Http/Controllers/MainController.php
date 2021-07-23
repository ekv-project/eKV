<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use Illuminate\Support\Facades\Schema;

class MainController extends Controller
{
    /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $instituteSettings;
    public function __construct()
    {
        // Check if system is properly installed. If not redirect to /install route.
        $this->middleware(function ($request, $next) {
            // Check if users table exist
            if(Schema::hasTable('users')){
                // Check if admin user not exist
                if(!User::where('username', 'admin')->first()){
                    return redirect()->route('install.view');
                }else{       
                    $this->instituteSettings = InstituteSetting::find(1);
                    return $next($request);
                }
            }else{
                return redirect()->route('install.view');
            }
        });
    }
}
