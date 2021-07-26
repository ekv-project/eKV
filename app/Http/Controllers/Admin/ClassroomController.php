<?php

namespace App\Http\Controllers\Admin;

use ID;
use App\Models\User;
use App\Models\Course;
use App\Models\Program;
use App\Models\Classroom;
use App\Models\StudyLevel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;
use App\Models\ClassroomCoordinator;
use App\Http\Controllers\MainController;

class ClassroomController extends MainController
{
    /**
     * Handling Views
     */
    public function view(Request $request){
        $pagination = 15;
        $classroom = Classroom::paginate($pagination)->withQueryString();
        if(ClassroomCoordinator::all()->count() > 0){
            $classroomCoordinator = ClassroomCoordinator::all();
        }else{
            $classroomCoordinator = [];
        }
        // Check for filters and search
        if($request->has('sort_by') AND $request->has('sort_order') AND $request->has('search')){
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if($search != NULL){
                $classroom = Classroom::where('id', 'LIKE', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%")->orWhere('programs_code', 'LIKE', "%{$search}%")->orWhere('admission_year', 'LIKE', "%{$search}%")->orWhere('study_levels_code', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }else{
                $classroom = Classroom::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search
            ];
            return view('dashboard.admin.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas', 'classroom' => $classroom, 'classroomCoordinator' => $classroomCoordinator,'filterAndSearch' => $filterAndSearch]);
        }else{
            return view('dashboard.admin.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas', 'classroom' => $classroom, 'classroomCoordinator' => $classroomCoordinator]);
        }
    }
    public function addView(){
        return view('dashboard.admin.classroom.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Kelas']);
    }
    public function updateView($id){
        if(Classroom::where('id', $id)->first()){
            if(ClassroomCoordinator::where('classrooms_id', $id)->first()){
                $classroomCoordinator = ClassroomCoordinator::join('users', 'classroom_coordinators.users_username', 'users.username')->select('users.username', 'users.fullname', 'users.email')->first();
            }else{
                $classroomCoordinator = NULL;
            }
            $classroom = Classroom::where('id', $id)->first();
            return view('dashboard.admin.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Kelas', 'classroom' => $classroom, 'classroomCoordinator' => $classroomCoordinator]);
        }else{
            abort(404, 'Kelas tidak dijumpai!');
        }
    }
    /**
     * Handling POST Request
     */
    public function add(Request $request){
        $validated = $request->validate([
            'name' => ['required'],
            'programs_code' => ['required'],
            'admission_year' => ['required', 'date_format:Y'],
            'study_year' => ['required', 'date_format:Y'],
            'study_levels_code' => ['required']
        ]);
        $name = strtolower($request->name);
        $programs_code = strtolower($request->programs_code);
        $admission_year = strtolower($request->admission_year);
        $study_year = strtolower($request->study_year);
        $study_levels_code = strtolower($request->study_levels_code);
        // Check if program existed . If not exist, return an error
        if(Program::where('code', $programs_code)->first()){
            // Check if study level existed . If not exist, return an error
            if(StudyLevel::where('code', $study_levels_code)->first()){
                // Check if classroom with the generated ID is existed. If yes, new ID will be generated until then ID have not been used before
                $classroomID = Str::random(10);
                while(Classroom::where('id', $classroomID)->first()){
                    $classroomID = Str::random(10);
                }
                Classroom::upsert([
                    [
                        'id' => $classroomID,
                        'name' => $name,
                        'programs_code' => strtolower($programs_code),
                        'admission_year' => strtolower($admission_year),
                        'study_year' => strtolower($study_year),
                        'study_levels_code' => strtolower($study_levels_code),
                        'active_status' => 1
                    ]
                ], ['id'], ['name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code', 'active_status']);
                session()->flash('classroomAddSuccess', 'Kelas berjaya ditambah!');
                session()->flash('classroomID', $classroomID);
                return redirect()->back();
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'studyLevelNotExisted' => 'Tahap Pengajian tidak dijumpai!'
                ]);
            }
        }else{
            return redirect()->back()->withInput()->withErrors([
                'programNotExisted' => 'Program tidak dijumpai!'
            ]);
        }
    }
    public function update(Request $request, $id){
        /**
         * WIP:
         * - Classroom Coordinator add and remove in update page
         */
        // Updating the classroom info
        if($request->has("classroom_update")){
            $validated = $request->validate([
                'name' => ['required'],
                'programs_code' => ['required'],
                'admission_year' => ['required', 'date_format:Y'],
                'study_year' => ['required', 'date_format:Y'],
                'study_levels_code' => ['required'],
                'active_status' => ['required', 'integer']
            ]);
            $classroomID = $request->id;
            $name = strtolower($request->name);
            $programs_code = strtolower($request->programs_code);
            $admission_year = strtolower($request->admission_year);
            $study_year = strtolower($request->study_year);
            $study_levels_code = strtolower($request->study_levels_code);
            // Check if classroom existed
            if(Classroom::where('id', $classroomID)->first()){
                Classroom::upsert([
                    [
                        'id' => $classroomID,
                        'name' => $name,
                        'programs_code' => strtolower($programs_code),
                        'admission_year' => strtolower($admission_year),
                        'study_year' => strtolower($study_year),
                        'study_levels_code' => strtolower($study_levels_code),
                        'active_status' => $request->active_status
                    ]
                ], ['id'], ['name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code', 'active_status']);
                session()->flash('classroomUpdateSuccess', 'Kelas berjaya dikemas kini!');
                return redirect()->back();
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'notExisted' => 'Kelas tidak wujud!'
                ]);
            }
        }elseif($request->has("add_coordinator")){
            /**
             * Classroom Coordinator Update
             * - A lecturer could be added as a coordinator in multiple classrooms at a time.
             * - A classroom could only have ONE lecturer as the coordinator.
             */
            // If the request is to add a coordinator to that classroom
            $validated = $request->validate([
                'coordinator_username' => ['required']
            ]);
            $coordinatorUsername = $request->coordinator_username;
            if(User::where('username', $coordinatorUsername)->first()){
                // Check if the classroom already have a coordinator.
                if(ClassroomCoordinator::where('classrooms_id', $id)->first()){
                    return redirect()->back();
                }else{
                    $userRole = User::where('username', $coordinatorUsername)->first()['role'];
                    // Only users with the lecturer can be a coordinator for a classroom.
                    if($userRole == 'lecturer'){
                        ClassroomCoordinator::create([
                            'users_username' => $coordinatorUsername,
                            'classrooms_id' => $id
                        ]);
                        session()->flash('successAdd', 'Pensyarah berjaya ditambah ke dalam kelas!');
                        return redirect()->back();
                    }else{
                        return redirect()->back()->withErrors([
                            'notALecturer' => 'Pengguna yang cuba ditambah bukannya seorang pensyarah!'
                        ]);
                    }
                }
            }else{
                return redirect()->back()->withErrors([
                    'noUser' => 'Tiada pengguna dijumpai!'
                ]);
            }
        }elseif($request->has("remove_coordinator")){
            // If the request is to remove coordinator from that classroom
            if(!empty($request->coordinator_username)){
                ClassroomCoordinator::where('users_username', $request->coordinator_username)->delete();
                session()->flash('successRemove', 'Pensyarah berjaya dibuang dari kelas!');
                return redirect()->back();
            }else{
                return redirect()->back();
            }
        }else{
            return redirect()->back();
        }
    }
    public function remove(Request $request){
        if(isset($request->id)){
            $classroomID = $request->id;
            if(Classroom::where('id', $classroomID)->first()){   
                Classroom::where('id', $classroomID)->delete();
                session()->flash('deleteSuccess', 'Kelas berjaya dibuang!');
                return redirect()->back();
            }else{
                return redirect()->back();
            }
        }
    }
}
