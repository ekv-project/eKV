<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    protected $systemSettings;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);;
    }
    /**
     * Handling views
     */
    public function view(){
        return view('dashboard.admin.system.view')->with(['settings' => $this->systemSettings, 'page' => 'Sistem']);
    }
    public function updateView(){
        return view('dashboard.admin.system.update')->with(['settings' => $this->systemSettings, 'page' => 'Sistem']);
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
        SystemSetting::updateOrCreate(
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
        session()->flash('systemUpdateSuccess', 'Tetapan sistem berjaya dikemas kini!');
        return redirect()->back();
    }
}
