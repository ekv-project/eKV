<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InstituteSetting;
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
    protected $instituteSettings;
    public function __construct()
    {
        $this->instituteSettings = InstituteSetting::find(1);
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
                $profile = UserProfile::join('users', 'users.username', 'user_profiles.users_username')->where('users_username', $username)->select('users.fullname', 'users.email', 'user_profiles.identification_number', 'user_profiles.phone_number', 'user_profiles.date_of_birth', 'user_profiles.place_of_birth', 'user_profiles.home_address', 'user_profiles.home_number', 'user_profiles.guardian_name', 'user_profiles.guardian_phone_number')->first();
                $noProfile = User::select('username', 'fullname', 'email')->where('username', $username)->first();
                return view('dashboard.user.profile.view')->with(['settings' => $this->instituteSettings, 'page' => 'Profil Pengguna', 'username' => $username, 'profile' => $profile, 'noProfile' => $noProfile]);
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
                return view('dashboard.user.profile.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Profil Pengguna', 'username' => $username, 'profile' => $profile]);
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
    
    public function download($username){
            //Only current authenticated user (their own profile), admin and their coordinator is allowed to download the profile.
        if(Gate::allows('authUser', $username) || Gate::allows('authCoordinator', $username) || Gate::allows('authAdmin')){
                //Check if student updated their profile
            if(UserProfile::where('users_username', $username)->first()){
                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'orientation' => 'P',
                ]);
                $mpdf->simpleTables = true;
                $mpdf->packTableData = true;
                $mpdf->keep_table_proportions = TRUE;
                $mpdf->shrink_tables_to_fit=1;
                $title = "Test";
                $mpdf->SetTitle($title);
                $mpdf->imageVars['profilePicture'] = file_get_contents(storage_path('app/public/img/profile/default/def-300.jpg'));
                $userProfile = UserProfile::where('users_username', $username)->first();
                $user = User::where('username', $username)->first();
                $stylesheet = '
                    p{
                        font-family: Arial;
                    }
                    .profile{
                        width: 100%;
                        margin: 5cm 0 0 0;
                    }
                    .header{
                        text-align: center;
                    }
                    .footer{
                        text-align: center;
                    }
                ';
                $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->SetHTMLHeader('
                            <div class="header" align="center">
                                <h1>KOLEJ VOKASIONAL MALAYSIA</h1>
                            </div>
                        ');
                $mpdf->SetHTMLFooter('
                    <div class="footer" align="center">
                        <p style="font-style: italic;">Slip profil ini adalah janaan komputer.</p>
                        <p style="font-style: italic;">Tandatangan tidak diperlukan.</p>
                    </div>
                ');
                $mpdf->WriteHTML('<div><br></div>');
                $profile = "
                    <div align='center' style='text-align: center; border: 2px solid black; border-radius: 20%; position: absolute; top: 13%; left: 40%; width: 3.5cm; height: 3.5cm; z-index: 1;'>
                        <img width='2.3cm' src='var:profilePicture' style='margin: 17% 0;'>
                    </div>
                    <div class='profile' style='z-index: 2;'>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 20%;'>
                                <p style='font-weight: bold;'>NAMA PENUH: </p>
                            </div>
                            <div style='float: right; width: 80%'>
                                <p style='font-weight: normal; float: right;'>" . strtoupper($user->fullname) . "</p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 50%'>
                                <p style='font-weight: bold;'>NO. KAD PENGENALAN: <span style='font-weight: normal;'>" . strtoupper($userProfile['identification_number']) . "</span></p>
                            </div>
                            <div style='float: right; width: 50%'>
                                <p style='font-weight: bold; float: right;'>NO. TELEFON PERIBADI: <span style='font-weight: normal;'>" . strtoupper($userProfile['phone_number']) . "</span></p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 50%'>
                                <p style='font-weight: bold;'>E-MEL: <span style='font-weight: normal;'>" . strtoupper($user->email) . "</span></p>
                            </div>
                            <div style='float: right; width: 50%'>
                                <p style='font-weight: bold; float: right;'>TARIKH LAHIR: <span style='font-weight: normal;'>" . strtoupper($userProfile['date_of_birth']) . "</span></p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 20%;'>
                                <p style='font-weight: bold;'>TEMPAT LAHIR: </p>
                            </div>
                            <div style='float: right; width: 80%'>
                                <p style='font-weight: normal; float: right;'>" . strtoupper($userProfile['place_of_birth']) . "</p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 20%;'>
                                <p style='font-weight: bold;'>ALAMAT RUMAH: </p>
                            </div>
                            <div style='float: right; width: 80%'>
                                <p style='font-weight: normal; float: right;'>" . strtoupper($userProfile['home_address']) . "</p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 20%;'>
                                <p style='font-weight: bold;'>NO. TELEFON RUMAH: </p>
                            </div>
                            <div style='float: right; width: 80%'>
                                <p style='font-weight: normal; float: right;'>" . strtoupper($userProfile['home_number']) . "</p>
                            </div>
                        </div>
                        <div class='row' style='width: 100%; height: 2.5%; margin: 1% 0;'>
                            <div style='float: left; width: 50%'>
                                <p style='font-weight: bold;'>NAMA PENJAGA: <span style='font-weight: normal;'>" . strtoupper($userProfile['guardian_name']) . "</span></p>
                            </div>
                            <div style='float: right; width: 50%'>
                                <p style='font-weight: bold; float: right;'>NO. TELEFON PENJAGA: <span style='font-weight: normal;'>" . strtoupper($userProfile['guardian_phone_number']) . "</span></p>
                            </div>
                        </div>
                    </div>
                ";
                $mpdf->WriteHTML($profile);
                $mpdf->Output('test.pdf', 'I');
            }else{
                abort(404, 'Profil pelajar tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
}
