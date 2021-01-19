<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstituteSetting;
use App\Models\StudyLevel;
use Illuminate\Http\Request;

class StudyLevelController extends Controller
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
        $studyLevel = StudyLevel::paginate($pagination)->withQueryString();
            // Check for filters and search
        if($request->has('sort_by') AND $request->has('sort_order') AND $request->has('search')){
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if($search != NULL){
                $studyLevel = StudyLevel::where('code', 'LIKE', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }else{
                $studyLevel = StudyLevel::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search
            ];
            return view('dashboard.admin.studylevel.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Tahap Pengajian', 'studyLevel' => $studyLevel, 'filterAndSearch' => $filterAndSearch]);
        }else{
            return view('dashboard.admin.studylevel.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Tahap Pengajian', 'studyLevel' => $studyLevel]);
        }
    }
    public function addView(){
        return view('dashboard.admin.studylevel.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Tahap Pengajian']);
    }
    public function updateView($code){
        if(StudyLevel::where('code', $code)->first()){
            $studyLevel = StudyLevel::where('code', $code)->first();
            return view('dashboard.admin.studylevel.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Program', 'studyLevel' => $studyLevel]);
        }else{
            abort(404, 'Tahap Pengajian tidak dijumpai');
        }
    }
    /**
     * Handling POST Request
     */
    public function add(Request $request){
        $validated = $request->validate([
            'study_level_code' => ['required'],
            'study_level_name' => ['required'],
            'total_semester' => ['required', 'numeric']
        ]);
        $code = $request->study_level_code;
        $name = $request->study_level_name;
        $totalSemester = $request->total_semester;
        // Check if program existed
        if(!StudyLevel::where('code', $code)->first()){
            StudyLevel::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'total_semester' => strtolower($totalSemester)
                ]
            ], ['code'], ['code', 'name']);
            session()->flash('studyLevelAddSuccess', 'Tahap Pengajian berjaya ditambah!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'existed' => 'Tahap Pengajian telah wujud!'
            ]);
        }
    }
    public function update(Request $request){
        $validated = $request->validate([
            'study_level_code' => ['required'],
            'study_level_name' => ['required'],
            'total_semester' => ['required', 'numeric']
        ]);
        $code = $request->study_level_code;
        $name = $request->study_level_name;
        $totalSemester = $request->total_semester;
        // Check if study level existed
        if(StudyLevel::where('code', $code)->first()){
            StudyLevel::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'total_semester' => strtolower($totalSemester)
                ]
            ], ['code'], ['code', 'name', 'total_semester']);
            session()->flash('studyLevelUpdateSuccess', 'Tahap Pengajian berjaya dikemas kini!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Tahap Pengajian tidak wujud!'
            ]);
        }
    }
    public function remove(Request $request){
        if(isset($request->study_level_code)){
            $code = $request->study_level_code;
            if(StudyLevel::where('code', $code)->first()){   
                StudyLevel::where('code', $code)->delete();
                session()->flash('deleteSuccess', 'Tahap Pengajian berjaya dibuang!');
                return redirect()->back();
            }
        }
    }
}
