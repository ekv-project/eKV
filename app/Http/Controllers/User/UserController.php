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
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

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
        if($request->has("addOne")){
            /**
             * Add one user at a time
             */
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
        }elseif($request->has("addBulk")){
            /**
             * Add multiple users at a time with XLSX template
             * Handle XLSX with Phpspreadsheet library
             */
            $validated = $request->validate([
                'user-xlsx' => ['required']
            ]);
            $userXLSX = $request->file('user-xlsx');
            $reader = new Xlsx();
            $reader->setReadDataOnly(true);
            //Read XLSX file.
            $spreadsheet = $reader->load($userXLSX);
            $dataArray = $spreadsheet->getActiveSheet()
                ->rangeToArray(
                    'A6:E106',     // The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    FALSE         // Should the array be indexed by cell row and cell column
                );
            $userArray = array();
            foreach ($dataArray as $row) {
                //Check if one of the row field is empty (if yes, don't proceed that row).
                if(!empty($row[0]) AND !empty($row[1]) AND !empty($row[2]) AND !empty($row[3]) AND !empty($row[4])){
                    //Check if role is equal to admin, student or lecturer
                    if(strtolower($row[4]) == 'admin' || strtolower($row[4]) == 'student' || strtolower($row[4]) == 'lecturer'){
                        //Check if email is valid
                        if(filter_var($row[2], FILTER_VALIDATE_EMAIL)){
                            //If all good, insert the row into an array.
                            $user = ['fullname' => strtolower($row[0]), 'username' => strtolower($row[1]), 'email' => strtolower($row[2]), 'password' => Hash::make($row[3]), 'role' => strtolower($row[4])];
                            array_push($userArray, $user);
                        }
                    }
                }
            }
            //The array will be imported into the database using Eloquent Upsert() method
            //by checking the username. This means if there's already existed user, it'll just
            //update it with the data from XLSX file.    
            User::upsert($userArray, ['username'], ['fullname', 'username', 'email', 'password'], 'role');
            session()->flash('userBulkAddSuccess', 'Pengguna berjaya ditambah!');
            return redirect()->back();
            }
    }
}
