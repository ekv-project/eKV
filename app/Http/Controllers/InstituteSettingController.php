<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class InstituteSettingController extends MainController
{
    /**
     * Handling views.
     */
    public function view(Request $request)
    {
        return view('dashboard.admin.institute.view')->with(['settings' => $this->instituteSettings, 'page' => 'Sistem']);
    }

    public function updateView(Request $request)
    {
        return view('dashboard.admin.institute.update')->with(['settings' => $this->instituteSettings, 'page' => 'Sistem']);
    }

    /**
     * Handling POST request.
     */
    public function updateSettings(Request $request)
    {
        if ($request->has('info')) {
            // User's profile update
            $validated = $request->validate([
                'institute_name' => ['required'],
                'institute_address' => ['required'],
                'institute_email_address' => ['required'],
                'institute_phone_number' => ['required'],
                'institute_fax' => ['required'],
            ]);
            InstituteSetting::updateOrCreate(
                ['id' => 1],
                [
                    'institute_name' => strtolower($request->institute_name),
                    'institute_address' => strtolower($request->institute_address),
                    'institute_email_address' => strtolower($request->institute_email_address),
                    'institute_phone_number' => strtolower($request->institute_phone_number),
                    'institute_fax' => strtolower($request->institute_fax),
                ]
            );
            // value="@php if(old('study_year') !== null){echo old('study_year');}elseif(isset($classroomData['study_year'])){echo $classroomData['study_year'];}else{echo NULL;} @endphp"
            session()->flash('instituteUpdateSuccess', 'Maklumat institut berjaya dikemas kini!');

            return redirect()->back();
        } elseif ($request->has('logo')) {
            $validated = $request->validate([
                'institute-logo' => ['required'],
            ]);
            $logo = $request->file('institute-logo');
            $logoExtension = $logo->extension();
            if ('png' == $logoExtension || 'jpeg' == $logoExtension || 'jpg' == $logoExtension || 'gif' == $logoExtension) {
                // Only PNG, JPEG and GIF images is supported
                // Save image to PNG (300x300 pixels)
                $image = Image::make($logo)->resize(300, 300)->encode('png');
                Storage::disk('public')->put('/img/system/logo-300.png', $image);
                session()->flash('logoSuccess', 'Logo insitut berjaya dikemas kini!');

                return redirect()->back();
            } else {
                return redirect()->back()->withErrors([
                    'unsupportedType' => 'Hanya gambar jenis PNG, JPEG dan GIF sahaja yang disokong!',
                ]);
            }
        }
    }
}
