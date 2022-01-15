<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\MainController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class UserController extends MainController
{
    /**
     * Handling Views
     */
    public function adminUserView(Request $request){
        $pagination = 15;
        $user = User::paginate($pagination)->withQueryString();
            // Check for filters and search
        if($request->has('sort_by') AND $request->has('sort_order') AND $request->has('search')){
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if($search != NULL){
                $user = User::where('username', 'LIKE', "%{$search}%")->orWhere('fullname', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('role', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }else{
                $user = User::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search
            ];
            return view('dashboard.admin.user.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengguna', 'user' => $user, 'filterAndSearch' => $filterAndSearch]);
        }else{
            return view('dashboard.admin.user.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Pengguna', 'user' => $user]);
        }
    }
    public function adminAddUserView(){
        return view('dashboard.admin.user.add')->with(['page' => 'Tambah Pengguna']);
    }
    public function adminUpdateUserView($username){
        if(User::where('username', $username)->first()){
            $user = User::where('username', $username)->first();
            return view('dashboard.admin.user.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Pengguna', 'user' => $user]);
        }else{
            abort(404, 'Pengguna tidak dijumpai!');
        }
    }
    /**
     * Handling POST Request
     */
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
                $id = User::select('id')->where('username', '=', $username)->first()->id;
                $user = User::find($id);
                $token = $user->createToken('apitoken');
                // Add Bearer API Token to session
                $request->session()->put('bearerAPIToken', $token->plainTextToken);
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
        if(auth()->user()->tokens() != NULL){
            auth()->user()->tokens()->delete();
        }
        // Remove Bearer API Token from session
        if ($request->session()->has('bearerAPIToken')) {
            $request->session()->forget('bearerAPIToken');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    public function adminAddUser(Request $request){
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

                // If no user existed, add them
                $validated = $request->validate([
                    'username' => ['required'],
                    'fullname' => ['required'],
                    'gender' => ['required'],
                    'email' => ['required', 'email:rfc'],
                    'password' => ['required', 'confirmed'],
                    'role' => ['required']
                ]);

                switch ($request->input('gender')) {
                    case 'male':
                        $userGender = 0;
                        break;
                    case 'female':
                        $userGender = 1;
                        break;
                    case 'notapplicable':
                        $userGender = 2;
                        break;
                    default:
                        return redirect()->back()->withInput()->withErrors([
                            'gender' => 'Input tidak diketahui!'
                        ]);
                        break;
                }

                User::create([
                    'username' => strtolower($username),
                    'fullname' => strtolower($request->fullname),
                    'gender' => $userGender,
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
            $requestExtension = $request->file('user-xlsx')->extension();
            if($requestExtension == 'xlsx'){
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
            }else{
                return redirect()->back()->withErrors([
                    "unsupportedType" => "Hanya file XLSX yang disokong, sila gunakan templat yang disediakan!"
                ]);
            }
        }
    }
    public function adminUpdateUser(Request $request){
        $validated = $request->validate([
            'username' => ['required'],
            'fullname' => ['required'],
            'email' => ['required', 'email:rfc']
        ]);
        $username = $request->username;
        $fullname = $request->fullname;
        $email = $request->email;
        // Check if user existed
        if(User::where('username', $username)->first()){
            User::where('username', $username)->update([
                'username' => strtolower($username),
                'fullname' => strtolower($fullname),
                'email' => strtolower($email)
            ]);
            session()->flash('userUpdateSuccess', 'Pengguna berjaya dikemas kini!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Pengguna tidak wujud!'
            ]);
        }
    }
    public function remove(Request $request){
        if(isset($request->username)){
            $username = $request->username;
            if($username == 'admin'){
                session()->flash('deleteError', 'Pengguna ini tidak boleh dibuang!');
                return redirect()->back();
            }else{
                if(User::where('username', $username)->first()){
                    User::where('username', $username)->delete();
                    session()->flash('deleteSuccess', 'Pengguna berjaya dibuang!');
                    return redirect()->back();
                }
            }
        }
    }
}
