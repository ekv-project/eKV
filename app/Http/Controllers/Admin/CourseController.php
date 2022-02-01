<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Program;
use App\Models\CourseSet;
use App\Models\StudyLevel;
use Illuminate\Http\Request;
use App\Models\CourseSetCourse;
use App\Http\Controllers\MainController;

class CourseController extends MainController
{
    /**
     * Handling Views.
     */
    public function view(Request $request)
    {
        $pagination = 15;
        $course = Course::paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                $course = Course::where('code', 'LIKE', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            } else {
                $course = Course::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.course.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kursus', 'course' => $course, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.course.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kursus', 'course' => $course]);
        }
    }

    public function addView()
    {
        return view('dashboard.admin.course.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Kursus']);
    }

    public function updateView($code)
    {
        if (Course::where('code', $code)->first()) {
            $course = Course::where('code', $code)->first();

            return view('dashboard.admin.course.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Kursus', 'course' => $course]);
        } else {
            abort(404, 'Kursus tidak dijumpai!');
        }
    }

    public function setView(Request $request)
    {
        $pagination = 15;
        $course = CourseSet::paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                $course = CourseSet::where('id', 'LIKE', "%{$search}%")->orWhere('study_levels_code', 'LIKE', "%{$search}%")->orWhere('programs_code', 'LIKE', "%{$search}%")->orWhere('semester', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            } else {
                $course = CourseSet::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.course.set.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Set Kursus', 'course' => $course, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.course.set.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Set Kursus', 'course' => $course]);
        }
    }

    public function setAddView()
    {
        $studyLevels = StudyLevel::select('code', 'name', 'total_semester')->get();
        $maxSemester = StudyLevel::select('total_semester')->get()->max()['total_semester'];
        return view('dashboard.admin.course.set.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Set Kursus', 'studyLevels' => $studyLevels, 'maxSemester' => $maxSemester]);
    }

    /**
     * Handling POST Request.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'course_code' => ['required'],
            'course_name' => ['required'],
            'credit_hour' => ['required', 'integer', 'max:10'],
            'total_hour' => ['required', 'integer', 'max:10'],
            'category' => ['required', 'integer', 'max:10'],
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        $creditHour = $request->credit_hour;
        $totalHour = $request->total_hour;
        // 1 = Pengajian Umum
        // 2 = Teras
        // 3 = Pengkhususan
        // 4 = Elektif
        // 5 = On-The-Job Training
        $category = $request->category;

        // Check if course existed
        if (!Course::where('code', $code)->first()) {
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'credit_hour' => $creditHour,
                    'total_hour' => $totalHour,
                    'category' => $category,
                ],
            ], ['code'], ['code', 'name', 'credit_hour', 'total_hour', 'category']);
            session()->flash('courseAddSuccess', 'Kursus berjaya ditambah!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'existed' => 'Kursus telah wujud!',
            ]);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'course_code' => ['required'],
            'credit_hour' => ['required', 'integer', 'max:10'],
            'total_hour' => ['required', 'integer', 'max:10'],
            'category' => ['required', 'integer', 'max:10'],
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        $creditHour = $request->credit_hour;
        $totalHour = $request->total_hour;
        // 1 = Pengajian Umum
        // 2 = Teras
        // 3 = Pengkhususan
        // 4 = Elektif
        // 5 = On-The-Job Training
        $category = $request->category;
        // Check if course existed
        if (Course::where('code', $code)->first()) {
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'credit_hour' => $creditHour,
                    'total_hour' => $totalHour,
                    'category' => $category,
                ],
            ], ['code'], ['code', 'name', 'credit_hour', 'total_hour', 'category']);
            session()->flash('courseUpdateSuccess', 'Kursus berjaya dikemas kini!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Kursus tidak wujud!',
            ]);
        }
    }

    public function remove(Request $request)
    {
        if (isset($request->code)) {
            $code = $request->code;
            if (Course::where('code', $code)->first()) {
                Course::where('code', $code)->delete();
                session()->flash('deleteSuccess', 'Kursus berjaya dibuang!');

                return redirect()->back();
            }
        }
    }

    public function setAdd(Request $request){
        $validated = $request->validate([
            'study_level' => ['required'],
            'semester' => ['required', 'integer', 'max:10'],
            'program' => ['required'],
        ]);
        if(!empty($request->input('course_code'))){
            if(Program::where('code', $request->input('program'))->first()){
                $courses = $request->input('course_code');
                $courseErr = [];
                foreach($courses as $course){
                    if(!Course::where('code', $course)->first()){
                        // Course not found
                        $error = '[Kod Kursus: ' . $course . ']' . ' Kursus tidak wujud!';
                        array_push($courseErr, $error);
                    }
                }

                if (count($courseErr) > 0) {
                    // Return errors if available
                    $request->session()->flash('courseErr', $courseErr);
                    return redirect()->back()->withInput();
                }else{
                    $studyLevelCode = $request->input('study_level');
                    $programCode = $request->input('program');
                    $semester = $request->input('semester');

                    if(!CourseSet::where('study_levels_code', $studyLevelCode)->where('programs_code', $programCode)->where('semester', $semester)->first()){
                        $courseSet = CourseSet::create([
                            'study_levels_code' => strtolower($studyLevelCode),
                            'programs_code' => strtolower($programCode),
                            'semester' => $semester,
                        ]);

                        foreach($courses as $course){
                            CourseSetCourse::create([
                                'course_sets_id' => $courseSet->id,
                                'courses_code' => strtolower($course),
                            ]);
                        }

                        session()->flash('courseSetAddSuccess', 'Set kursus berjaya ditambah!');
                        return redirect()->back();
                    }else{
                        return redirect()->back()->withInput()->withErrors([
                            'existed' => 'Set Kursus dengan tahap pengajian, kod program dan semester yang sama telah wujud!',
                        ]);
                    }

                }
            }else{
                return redirect()->back()->withInput()->withErrors([
                    'program' => 'Program tidak dijumpai!',
                ]);
            }
        }else{
            return redirect()->back()->withInput()->withErrors([
                'courses_empty' => 'Tiada kursus ditambah!',
            ]);
        }
    }
}
