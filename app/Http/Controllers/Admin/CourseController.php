<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstituteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
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
    /***************************************************************************/
    /**
     * Handling Views
     */
    public function view(Request $request){
        $pagination = 15;
        $course = Course::paginate($pagination)->withQueryString();
            // Check for filters and search
        if($request->has('sort_by') AND $request->has('sort_order') AND $request->has('search')){
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if($search != NULL){
                $course = Course::where('code', 'LIKE', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }else{
                $course = Course::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search
            ];
            return view('dashboard.admin.course.view')->with(['settings' => $this->instituteSettings, 'apiToken' => $this->apiToken, 'page' => 'Senarai Kursus', 'course' => $course, 'filterAndSearch' => $filterAndSearch]);
        }else{
            return view('dashboard.admin.course.view')->with(['settings' => $this->instituteSettings, 'apiToken' => $this->apiToken, 'page' => 'Senarai Kursus', 'course' => $course]);
        }
    }
    public function addView(){
        return view('dashboard.admin.course.add')->with(['settings' => $this->instituteSettings, 'apiToken' => $this->apiToken, 'page' => 'Tambah Kursus']);
    }
    public function updateView($code){
        if(Course::where('code', $code)->first()){
            $course = Course::where('code', $code)->first();
            return view('dashboard.admin.course.update')->with(['settings' => $this->instituteSettings, 'apiToken' => $this->apiToken, 'page' => 'Kemas Kini Kursus', 'course' => $course]);
        }else{
            abort(404, 'Kursus tidak dijumpai!');
        }
    }
    /**
     * Handling POST Request
     */
    public function add(Request $request){
        $validated = $request->validate([
            'course_code' => ['required'],
            'course_name' => ['required']
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        // Check if course existed
        if(!Course::where('code', $code)->first()){
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name)
                ]
            ], ['code'], ['code', 'name']);
            session()->flash('courseAddSuccess', 'Kursus berjaya ditambah!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'existed' => 'Kursus telah wujud!'
            ]);
        }
    }
    public function update(Request $request){
        $validated = $request->validate([
            'course_code' => ['required'],
            'course_name' => ['required']
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        // Check if course existed
        if(Course::where('code', $code)->first()){
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name)
                ]
            ], ['code'], ['code', 'name']);
            session()->flash('courseUpdateSuccess', 'Kursus berjaya dikemas kini!');
            return redirect()->back();
        }else{
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Kursus tidak wujud!'
            ]);
        }
    }
    public function remove(Request $request){
        if(isset($request->code)){
            $code = $request->code;
            if(Course::where('code', $code)->first()){   
                Course::where('code', $code)->delete();
                session()->flash('deleteSuccess', 'Kursus berjaya dibuang!');
                return redirect()->back();
            }
        }
    }
}
