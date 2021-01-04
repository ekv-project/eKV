<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $systemSettings;
    protected $currentUserUsername;
    protected $apiToken;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);
        $this->middleware(function ($request, $next) {      
            $this->currentUserUsername = 'admin';
            $this->apiToken = User::where('username', $this->currentUserUsername)->select('api_token')->first();
            return $next($request);
        });
    }
    /***************************************************************************/

    public function login(Request $request){
        $username = strtolower($request->username);
        if(User::where('username', '=', $username)->count() > 0){
            $credentials = [
                'username' => $username,
                'password' => $request->password
            ];   
            if(Auth::attempt($credentials)) {
                $request->session()->regenerate();
                LoginActivity::create([
                    'users_username' => $username,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->server('HTTP_USER_AGENT')
                ]);
                $user = User::find($username);
                $user->api_token = Str::random(60);
                $user->save();
                return redirect()->intended('dashboard');
            }else{
                return back()->withInput()->withErrors([
                    'password' => 'Kata Laluan Salah.'
                ]);
            }
        }else{
            return back()->withInput()->withErrors([
                'username' => 'Pengguna tidak wujud.'
            ]);
        }
    }
    public function logout(Request $request){
        $user = User::find(Auth::user()->username);
        $user->api_token = null;
        $user->save();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Add a new user
     */
    public function addNewUser(Request $request){
        // Check if user already exist
        $username = strtolower($request->username);
        if(User::where('username', $username)->first()){
            return back()->withErrors([
                'userExist' => 'Pengguna telah wujud!',
            ]);
        }else{
        // If no user exist, add them
            $validated = $request->validate([
                'fullname' => ['required'],
                'username' => ['required'],
                'email' => ['required', 'email:rfc'],
                'password' => ['required', 'confirmed'],
                'role' => ['required']
            ]);
            // If validation failed, display the error
            User::create([
                'fullname' => strtolower($request->fullname),
                'username' => strtolower($username),
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'role' => strtolower($request->role),
            ]);
            session()->flash('userAddSuccess', 'Pengguna berjaya ditambah!');
            return redirect()->back();
        }
    }
    /**
     * Add multiple users at a time with XLSX file
     * Handle XLSX with Phpspreadsheet library
     */
    public function bulkAddNewUser(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/') . 'hello world.xlsx');
    }
}
