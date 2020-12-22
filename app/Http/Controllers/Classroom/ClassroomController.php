<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomCoordinator;
use App\Models\ClassroomStudent;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassroomController extends Controller
{
    public $systemSettings;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);
    }
    /**
     * Handling Views
     */
    public function classroom(){
        // If authenticated user is a student or coordinator of a classroom, they will be redirected to their own classroom.
        if(isset(ClassroomCoordinator::where('users_username', Auth::user()->username)->first()->classroom)){
            $classroomCoordinator = ClassroomCoordinator::where('users_username', Auth::user()->username)->first()->classroom;
            return redirect()->route('classroom.view', ['classroomID' => $classroomCoordinator->id]);
        }elseif(isset(ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom)){
            $classroomStudent = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom;
            return redirect()->route('classroom.view', ['classroomID' => $classroomStudent->id]);
        }else{
            return redirect()->route('dashboard');
        }
    }
    public function view($classroomID){
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            return view('dashboard.user.classroom.view')->with(['settings' => $this->systemSettings, 'page' => 'Maklumat Kelas']);
        }elseif(!empty(ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom)){
            $classroomStudentID = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom->id;
            if($classroomStudentID == $classroomID){
                return view('dashboard.user.classroom.view')->with(['settings' => $this->systemSettings, 'page' => 'Maklumat Kelas']);
            }else{
                abort(403, 'Anda tiada akses paga laman ini');
            }
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
        
        
    }
    public function student($classroomID){
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            return view('dashboard.user.classroom.view')->with(['settings' => $this->systemSettings, 'page' => 'Maklumat Kelas']);
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
    public function update($classroomID){
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            return view('dashboard.user.classroom.view')->with(['settings' => $this->systemSettings, 'page' => 'Maklumat Kelas']);
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
    /**
     * Handling POST Request
     */
}
