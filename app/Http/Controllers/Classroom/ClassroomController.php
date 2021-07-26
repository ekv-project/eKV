<?php

namespace App\Http\Controllers\Classroom;

use App\Models\User;
use App\Models\Program;
use App\Models\Classroom;
use App\Models\StudyLevel;
use Illuminate\Http\Request;
use App\Models\ClassroomStudent;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;
use App\Models\ClassroomCoordinator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\MainController;

class ClassroomController extends MainController
{

    /**
     * Handling Views
     */
    public function classroom(){
        // If user a student, redirect to their classroom. 
        if(isset(ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom)){
            $classroomStudent = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom;
            return redirect()->route('classroom.view', ['classroomID' => $classroomStudent->id]);
        }elseif(count(ClassroomCoordinator::where('users_username', Auth::user()->username)->get()) > 0){
            // If user a coordinator, list all the classes associated with them.
            $classroomList = ClassroomCoordinator::where('users_username', Auth::user()->username)->with('classroom')->paginate(10);
            return view('dashboard.user.classroom.coordinator.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas Koordinator', 'classroomList' => $classroomList]);
        }elseif(Gate::allows('authAdmin')){
            // If user an admin, redirect to classroom section in admin dashboard
            return redirect()->route('admin.classroom');
        }else{
            abort(404, 'Anda tidak menjadi koordinator di mana-mana kelas');
        } 
    }
    public function view($classroomID){
        if(ClassroomStudent::where('classrooms_id', $classroomID)->first()){
            $students = ClassroomStudent::select('users.username', 'users.fullname', 'users.email')->where('classrooms_id', $classroomID)->join('users', 'users.username', 'classroom_students.users_username')->get();
        }else{
            $students = NULL;
        }
        if(Classroom::where('id', $classroomID)->first()){
            $classroomInfo = Classroom::where('id', $classroomID)->first();
        }else{
            $classroomInfo = NULL;
        }
        if(ClassroomCoordinator::select('users_username')->where('classrooms_id', $classroomID)->first()){
            $classroomCoordinator = ClassroomCoordinator::select('users_username')->where('classrooms_id', $classroomID)->first();
        }else{
            $classroomCoordinator = "";
        }
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            // Check if user is a coordinator for the classroom or an admin
            return view('dashboard.user.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Maklumat Kelas', 'students' => $students, 'classroomInfo' => $classroomInfo, 'classroomCoordinator' => $classroomCoordinator]);
        }elseif(!empty(ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom)){
            // Check if student in a classroom
            $classroomStudentID = ClassroomStudent::where('users_username', Auth::user()->username)->first()->classroom->id;
            if($classroomStudentID == $classroomID){
                // Check if the student classroom is the one they trying to access
                return view('dashboard.user.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Maklumat Kelas', 'students' => $students, 'classroomInfo' => $classroomInfo, 'classroomCoordinator' => $classroomCoordinator]);
            }else{
                abort(403, 'Anda tiada akses paga laman ini');
            }
        }else{
            abort(403, 'Anda tiada akses pada laman ini');
        }
        
        
    }
    public function student($classroomID){
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            $students = ClassroomStudent::where('classrooms_id', $classroomID)->with('user')->get();
            $classroomData = Classroom::select('id', 'name', 'programs_code', 'admission_year', 'study_year')->where('id', $classroomID)->first();
            return view('dashboard.user.classroom.student')->with(['settings' => $this->instituteSettings, 'page' => 'Pelajar Kelas', 'students' => $students, 'classroomData' => $classroomData]);
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
    public function update($classroomID){
        if(Classroom::where('id', $classroomID)->count() < 1){
            abort(404, 'Tiada kelas dijumpai');
        }elseif(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            $classroomData = Classroom::select('id', 'name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code')->where('id', $classroomID)->first();
            return view('dashboard.user.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Maklumat Kelas', 'classroomData' => $classroomData]);
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
    /**
     * Handling POST Request
     */
    public function studentUpdate(Request $request, $classroomID){
        if(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            if($request->has("add")){
                // If the request is to add a student to that classroom
                if(!empty($request->studentID)){
                    if(User::where('username', $request->studentID)->first()){
                        $userRole = User::where('username', $request->studentID)->first()['role'];
                        if($userRole == 'student'){
                            // Check if the student is already in a classroom
                            $classroomStudent = ClassroomStudent::where('users_username', $request->studentID)->get()->count();
                            if($classroomStudent < 1){
                                ClassroomStudent::create([
                                    'users_username' => $request->studentID,
                                    'classrooms_id' => $classroomID
                                ]);
                                session()->flash('successAdd', 'Pelajar berjaya ditambah ke dalam kelas!');
                                return redirect()->back();
                            }elseif($classroomStudent > 0){
                                return redirect()->back()->withErrors([
                                    'existed' => 'Pelajar telah berada dalam kelas sebelum ini!'
                                ]);
                            }
                        }else{
                            return redirect()->back()->withErrors([
                                'notStudent' => 'Pengguna yang cuba ditambah bukannya seorang pelajar!'
                            ]);
                        }
                    }else{
                        return redirect()->back()->withErrors([
                            'noUser' => 'Tiada pengguna dijumpai!'
                        ]);
                    }
                }else{
                    return redirect()->route('dashboard');
                }
            }elseif($request->has("remove")){
                // If the request is to remove a student from that classroom
                if(!empty($request->username)){
                    ClassroomStudent::where('users_username', $request->username)->delete();
                    session()->flash('successRemove', 'Pelajar berjaya dibuang daripada kelas!');
                    return redirect()->back();
                }else{
                    return redirect()->back();
                }
            }else{
                return redirect()->back();
            }
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
    public function classroomUpdate(Request $request, $classroomID){
        if(Gate::allows('authCoordinator', $classroomID) || Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
            $validated = $request->validate([
                'name' => ['required'],
                'programs_code' => ['required'],
                'admission_year' => ['required', 'date_format:Y'],
                'study_year' => ['required', 'date_format:Y'],
                'study_levels_code' => ['required'],
                'active_status' => ['required', 'integer']
            ]);
            $program = Program::where('code', $request->programs_code)->get()->count();
            $studyLevel = StudyLevel::where('code', $request->study_levels_code)->get()->count();
            if($program > 0){
                if($studyLevel > 0){
                    Classroom::upsert([
                        [
                            'id' => $classroomID,
                            'name' => strtolower($request->name),
                            'programs_code' => strtolower($request->programs_code),
                            'admission_year' => strtolower($request->admission_year),
                            'study_year' => $request->study_year,
                            'study_levels_code' => strtolower($request->study_levels_code),
                            'active_status' => $request->active_status
                        ]
                    ], ['id'], ['name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code', 'active_status']);
                    session()->flash('classroomUpdateSuccess', 'Maklumat kelas berjaya dikemas kini!');
                    return redirect()->back();
                }elseif($studyLevel < 1){
                    return redirect()->back()->withInput()->withErrors([
                        'noStudyLevel' => 'Tahap pengajian tidak wujud!'
                    ]);
                }
            }elseif($program < 1){
                return redirect()->back()->withInput()->withErrors([
                    'noProgram' => 'Program tidak wujud!'
                ]);
            }
        }else{
            abort(403, 'Anda tiada akses paga laman ini');
        }
    }
}
