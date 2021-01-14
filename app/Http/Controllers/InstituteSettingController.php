<?php

namespace App\Http\Controllers;

use App\Models\InstituteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class InstituteSettingController extends Controller
{
    /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $instituteSettings;
    protected $currentUserUsername;
    protected $apiToken;
    public function __construct()
    {
        $this->instituteSettings = InstituteSetting::find(1);
        $this->middleware(function ($request, $next) {      
            $this->currentUserUsername = 'admin';
            $this->apiToken = User::where('username', $this->currentUserUsername)->select('api_token')->first();
            return $next($request);
        });
    }
    /**
     * Handling views
     */
    public function view(){
        return view('dashboard.admin.institute.view')->with(['settings' => $this->instituteSettings, 'page' => 'Sistem']);
    }
    public function updateView(){
        return view('dashboard.admin.institute.update')->with(['settings' => $this->instituteSettings, 'page' => 'Sistem']);
    }
    /**
     * Handling POST request
     */
    public function updateSettings(Request $request){
        // User's profile update
        $validated = $request->validate([
            'institute_name' => ['required'],
            'institute_address' => ['required'],
            'institute_email_address' => ['required'],
            'institute_phone_number' => ['required'],
            'institute_fax' => ['required']
        ]);
        InstituteSetting::updateOrCreate(
            ['id' => 1],
            [
                'institute_name' => strtolower($request->institute_name),
                'institute_address' => strtolower($request->institute_address),
                'institute_email_address' => strtolower($request->institute_email_address),
                'institute_phone_number' => strtolower($request->institute_phone_number),
                'institute_fax' => strtolower($request->institute_fax)
            ]
        );
        // value="@php if(old('study_year') !== null){echo old('study_year');}elseif(isset($classroomData['study_year'])){echo $classroomData['study_year'];}else{echo NULL;} @endphp"
        session()->flash('instituteUpdateSuccess', 'Maklumat institut berjaya dikemas kini!');
        return redirect()->back();
    }
}
