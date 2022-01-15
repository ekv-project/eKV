<?php

namespace App\Http\Controllers\User;

use PDF;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\MainController;

class UserProfileController extends MainController
{
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
                $profile = UserProfile::join('users', 'users.username', 'user_profiles.users_username')->where('users_username', $username)->select('users.fullname', 'users.email', 'user_profiles.identification_number', 'user_profiles.phone_number', 'user_profiles.gender', 'user_profiles.date_of_birth', 'user_profiles.place_of_birth', 'user_profiles.home_address', 'user_profiles.home_number', 'user_profiles.guardian_name', 'user_profiles.guardian_phone_number')->first();
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
            if(Gate::allows('authUser', $username) || Gate::allows('authAdmin')){
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
                    'gender' => ['required'],
                    'date_of_birth' => ['required'],
                    'place_of_birth' => ['required'],
                    'home_address' => ['required'],
                    'home_number' => ['required'],
                    'guardian_name' => ['required'],
                    'guardian_phone_number' => ['required']
                ]);
                UserProfile::upsert([
                    [
                        'users_username' => $username,
                        'identification_number' => strtolower($request->identification_number),
                        'phone_number' => strtolower($request->phone_number),
                        'gender' => strtolower($request->gender),
                        'date_of_birth' => $request->date_of_birth,
                        'place_of_birth' => strtolower($request->place_of_birth),
                        'home_address' => strtolower($request->home_address),
                        'home_number' => strtolower($request->home_number),
                        'guardian_name' => strtolower($request->guardian_name),
                        'guardian_phone_number' => strtolower($request->guardian_phone_number)
                    ]
                ],['users_username'],['identification_number', 'phone_number', 'gender', 'date_of_birth', 'place_of_birth', 'home_address', 'home_number', 'guardian_name', 'guardian_phone_number']);
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
                    // Save image to PNG (300x300 pixels)
                    $image = Image::make($picture)->resize(300, 300)->encode('png');
                    Storage::disk('public')->put('/img/profile/'. $username . '.png', $image);
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
                $profile = UserProfile::join('users', 'users.username', 'user_profiles.users_username')->where('users_username', $username)->select('users.fullname', 'users.email', 'user_profiles.identification_number', 'user_profiles.phone_number', 'user_profiles.gender', 'user_profiles.date_of_birth', 'user_profiles.place_of_birth', 'user_profiles.home_address', 'user_profiles.home_number', 'user_profiles.guardian_name', 'user_profiles.guardian_phone_number')->first();
                $title = "Slip Profil Pengguna - " . ucwords($profile['fullname']);
                PDF::SetCreator('eKV');
                PDF::SetAuthor('eKV');
                PDF::SetTitle($title);
                PDF::AddPage();
                PDF::SetFont('helvetica', 'B', 10);
                $settings = $this->instituteSettings;
                if(isset($settings)){
                    if(empty($settings['institute_name'])){
                        $instituteName = "Kolej Vokasional Malaysia";
                    }else{
                        $instituteName = ucwords($settings['institute_name']);
                    }
                }else{
                    $instituteName = "Kolej Vokasional Malaysia";
                }
                if(Storage::disk('local')->exists('public/img/system/logo-300.png')){
                    $logo = '.' . Storage::disk('local')->url('public/img/system/logo-300.png');
                }elseif(Storage::disk('local')->exists('public/img/system/logo-def-300.png')){
                    $logo = '.' . Storage::disk('local')->url('public/img/system/logo-def-300.png');
                }
                // Header
                PDF::Image($logo, 15, 10, 26, 26);
                PDF::SetFont('helvetica', 'B', 12);
                PDF::Multicell(0, 6, strtoupper($instituteName), 0, 'L', 0, 2, 42, 10);
                PDF::SetFont('helvetica', '', 9);
                PDF::Multicell(0, 10, 'ALAMAT INSTITUSI: ' . strtoupper($settings['institute_address']), 0, 'L', 0, 2, 42, 16);
                PDF::Multicell(0, 5, 'ALAMAT E-MEL: ' . strtoupper($settings['institute_email_address']), 0, 'L', 0, 2, 42, 26);
                PDF::Multicell(0, 5, 'NO. TELEFON PEJABAT: ' . strtoupper($settings['institute_phone_number']), 0, 'L', 0, 2, 42, 31);
                PDF::Ln(1);
                PDF::Line(10, 40, 200, 40, []);
                PDF::SetFont('helvetica', 'b', 10);
                PDF::Multicell(0, 5, 'SLIP PROFIL PENGGUNA', 0, 'C', 0, 2, 10, 42);
                PDF::Line(10, 48, 200, 48, []);
                PDF::Ln(1);
                // Profile Image
                if(Storage::disk('local')->exists('public/img/profile/' . $username . '.png')){
                    $profileImage = '.' . Storage::disk('local')->url('public/img/profile/' . $username . '.png');
                    PDF::Image($profileImage, 88, 52, 33, 33);
                }elseif(Storage::disk('local')->exists('public/img/profile/default/def-300.png')){
                    $profileImage = '.' . Storage::disk('local')->url('public/img/profile/default/def-300.png');
                    PDF::Image($profileImage, 88, 52, 33, 33);
                }
                //
                PDF::SetFont('helvetica', 'B', 10);
                PDF::Multicell(50, 5, 'NAMA ', 0, 'L', 0, 2, 10, 90);
                PDF::Multicell(50, 5, 'NO. KAD PENGENALAN ', 0, 'L', 0, 2, 10, 105);
                PDF::Multicell(50, 5, 'ALAMAT E-MEL: ', 0, 'L', 0, 2, 10, 120);
                PDF::Multicell(50, 5, 'NO. TELEFON PERIBADI ', 0, 'L', 0, 2, 10, 135);
                PDF::Multicell(50, 5, 'JANTINA ', 0, 'L', 0, 2, 10, 150);
                PDF::Multicell(50, 5, 'TARIKH LAHIR ', 0, 'L', 0, 2, 10, 165);
                PDF::Multicell(50, 5, 'TEMPAT LAHIR ', 0, 'L', 0, 2, 10, 180);
                PDF::Multicell(50, 5, 'ALAMAT RUMAH ', 0, 'L', 0, 2, 10, 195);
                PDF::Multicell(50, 5, 'NO. TELEFON RUMAH ', 0, 'L', 0, 2, 10, 210);
                PDF::Multicell(50, 5, 'NAMA PENJAGA ', 0, 'L', 0, 2, 10, 225);
                PDF::Multicell(50, 5, 'NO. TELEFON PENJAGA ', 0, 'L', 0, 2, 10, 240);
                //:
                PDF::SetFont('helvetica', 'B', 10);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 90);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 105);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 120);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 135);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 150);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 165);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 180);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 195);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 210);
                PDF::Multicell(0, 5, ':', 0, 'L', 0, 2, 60, 225);
                //
                PDF::SetFont('helvetica', '', 10);
                PDF::Multicell(0, 5, strtoupper($profile['fullname']), 0, 'L', 0, 2, 64, 90);
                PDF::Multicell(0, 5, strtoupper($profile['identification_number']), 0, 'L', 0, 2, 64, 105);
                PDF::Multicell(0, 5, strtoupper($profile['email']), 0, 'L', 0, 2, 64, 120);
                PDF::Multicell(0, 5, strtoupper($profile['phone_number']), 0, 'L', 0, 2, 64, 135);
                PDF::Multicell(0, 5, strtoupper($profile['gender']), 0, 'L', 0, 2, 64, 150);
                PDF::Multicell(0, 5, strtoupper($profile['date_of_birth']), 0, 'L', 0, 2, 64, 165);
                PDF::Multicell(0, 5, strtoupper($profile['place_of_birth']), 0, 'L', 0, 2, 64, 180);
                PDF::Multicell(0, 5, strtoupper($profile['home_address']), 0, 'L', 0, 2, 64, 195);
                PDF::Multicell(0, 5, strtoupper($profile['home_number']), 0, 'L', 0, 2, 64, 210);
                PDF::Multicell(0, 5, strtoupper($profile['guardian_name']), 0, 'L', 0, 2, 64, 225);
                PDF::Multicell(0, 5, strtoupper($profile['guardian_phone_number']), 0, 'L', 0, 2, 64, 240);
                // Footer
                PDF::SetXY(10, 265);
                PDF::SetFont('helvetica', '', 9);
                PDF::MultiCell(0, 5, 'Slip ini adalah janaan komputer.', 0, 'C', 0, 0, '', '', true);
                PDF::Ln(3);
                PDF::MultiCell(0, 5, 'Tandatangan tidak diperlukan.', 0, 'C', 0, 0, '', '', true);
                PDF::Ln(3);
                PDF::MultiCell(0, 5, 'Dijana menggunakan sistem eKV.', 0, 'C', 0, 0, '', '', true);
                PDF::Output($title, 'D');
            }else{
                abort(404, 'Profil pelajar tidak dijumpai!');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
}
