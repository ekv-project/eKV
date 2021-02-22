<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Program;
use App\Models\Classroom;
use App\Models\StudyLevel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;

class ClassroomController extends Controller
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
    public function view(Request $request){
        $pagination = 15;
        $classroom = Classroom::paginate($pagination)->withQueryString();
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
            return view('dashboard.admin.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas', 'classroom' => $classroom, 'filterAndSearch' => $filterAndSearch]);
        }else{
            return view('dashboard.admin.classroom.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas', 'classroom' => $classroom]);
        }
    }
    public function addView(){
        return view('dashboard.admin.classroom.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Kelas']);
    }
    public function updateView($id){
        if(Classroom::where('id', $id)->first()){
            $classroom = Classroom::where('id', $id)->first();
            return view('dashboard.admin.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Kelas', 'classroom' => $classroom]);
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
                        'study_levels_code' => strtolower($study_levels_code)
                    ]
                ], ['id'], ['name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code']);
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
    public function update(Request $request){
        /**
         * WIP:
         * - Classroom Coordinator add and remove
         */
        // Updating the classroom info
        $validated = $request->validate([
            'name' => ['required'],
            'programs_code' => ['required'],
            'admission_year' => ['required', 'date_format:Y'],
            'study_year' => ['required', 'date_format:Y'],
            'study_levels_code' => ['required']
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
                    'study_levels_code' => strtolower($study_levels_code)
                ]
            ], ['id'], ['name', 'programs_code', 'admission_year', 'study_year', 'study_levels_code']);
            session()->flash('classroomUpdateSuccess', 'Kelas berjaya dikemas kini!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Kelas tidak wujud!'
            ]);
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
