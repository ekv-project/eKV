<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserProfileController extends Controller
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

    /**
     * Handling Views
     */
    public function viewProfile(){
        // Redirect authenticated user to their own profile.
        return redirect()->route('profile.user', ['username' => Auth::user()->username]);
    }
    public function view($username){
        // Check if user exist. If true, return view
        if(User::where('username', '=', $username)->count() > 0){
            if(Gate::allows('authUser', $username) || Gate::allows('authCoordinator', $username) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
                $profile = UserProfile::where('users_username', $username)->first();
                return view('dashboard.user.profile.view')->with(['settings' => $this->systemSettings, 'page' => 'Profil Pengguna', 'username' => $username, 'profile' => $profile]);
            }else{
                abort(403, 'Anda tiada akses pada laman ini');
            }
        }else{
        // Check if user exist. Else, abort with 404.
            abort(404, 'Tiada pengguna dijumpai');
        }
    }
    // Only the current authenticated user can view their own profile update page
    public function updateView($username){
        // Check if user exist. True, return view.
        if(User::where('username', '=', $username)->count() > 0){
            if(Gate::allows('authUser', $username)){
                $profile = UserProfile::where('users_username', $username)->first();
                return view('dashboard.user.profile.update')->with(['settings' => $this->systemSettings, 'page' => 'Kemas Kini Profil Pengguna', 'username' => $username, 'profile' => $profile]);
            }else{
                abort(403, 'Anda tiada akses pada laman ini!');
            }
        }else{
        // Check if user exist. Else, abort with 404.
            abort(404, 'Tiada pengguna dijumpai!');
        }
    }

    /**
     * Handling POST Request
     */

    // Only the current authenticated user can update their profile
    public function update(Request $request, $username){
        if(Gate::allows('authUser', $username)){
            if($request->has("profile")){
                // User's profile update
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
                UserProfile::updateOrCreate(
                    ['users_username' => $username],
                    [
                        'identification_number' => strtolower($request->identification_number),
                        'phone_number' => strtolower($request->phone_number),
                        'date_of_birth' => $request->date_of_birth,
                        'place_of_birth' => strtolower($request->place_of_birth),
                        'home_address' => strtolower($request->home_address),
                        'home_number' => strtolower($request->home_number),
                        'guardian_name' => strtolower($request->guardian_name),
                        'guardian_phone_number' => strtolower($request->guardian_phone_number)
                    ]
                );
                session()->flash('profileUpdateSuccess', 'Profil berjaya dikemas kini!');
                return redirect()->back();
            }elseif($request->has("password")){
                // User's password change
                $validated = $request->validate([
                    'current_password' => ['required'],
                    'new_password' => ['required'],
                    'new_password_confirmation' => ['required'],
                ]);
                $currentPassword = User::select('password')->where('username', Auth::user()->username)->first()['password'];
                $requestCurrentPassword = $request->current_password;
                $requestNewPassword = $request->new_password;
                $requestNewPasswordConfirmation = $request->new_password_confirmation;
                if(Hash::check($requestCurrentPassword, $currentPassword)){
                    if($requestNewPassword == $requestNewPasswordConfirmation){
                        User::where('username', Auth::user()->username)->update(['password' => Hash::make($requestNewPassword)]);
                        session()->flash('passwordUpdateSuccess', 'Kata laluan berjaya diubah!');
                        return redirect()->back();
                    }else{
                        return redirect()->back()->withInput()->withErrors([
                            'newPasswordUpdate' => 'Pengesahan kata laluan baharu gagal!'
                        ]);
                    }
                }else{
                    return redirect()->back()->withInput()->withErrors([
                        'currentPasswordUpdate' => 'Kata laluan semasa salah!'
                    ]);
                }
            }elseif($request->has("picture")){
                $validated = $request->validate([
                    'profile-picture' => ['required']
                ]);
                $picture = $request->file('profile-picture');
                $pictureExtension = $picture->extension();
                if($pictureExtension == 'png'|| $pictureExtension == 'jpeg' || $pictureExtension == 'jpg' || $pictureExtension == 'gif'){
                    // Only PNG, JPEG and GIF images is supported
                    // Save image to JPEG (300x300 pixels)
                    Image::make($picture)->resize(300, 300)->save('public/img/profile/'. $username . '.jpg', 60);
                    session()->flash('pictureSuccess', 'Gambar profil berjaya dikemas kini!');
                    return redirect()->back();
                }else{
                    return redirect()->back()->withErrors([
                        "unsupportedType" => "Hanya gambar jenis PNG, JPEG dan GIF sahaja yang disokong!"
                    ]);
                }      
            }else{
                return redirect()->back();
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
    // Only current authenticated user (their own profile), admin and their coordinator is allowed to download the profile.
    public function download($username){
        if(Gate::allows('authUser', $username)){
            dd("user");
        }elseif(Gate::allows('authAdmin', $username)){
            dd("admin");
        }/*elseif(Gate::allows('authCoordinator', $username)){
            dd("coordinator");
        }*/
    }
}
