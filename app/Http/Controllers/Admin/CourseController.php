<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
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

    /**
     * Handling POST Request.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'course_code' => ['required'],
            'course_name' => ['required'],
            'credit_hour' => ['required', 'integer', 'max:10'],
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        $creditHour = $request->credit_hour;

        // Check if course existed
        if (!Course::where('code', $code)->first()) {
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'credit_hour' => $creditHour,
                ],
            ], ['code'], ['code', 'name', 'credit_hour']);
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
        ]);
        $code = $request->course_code;
        $name = $request->course_name;
        $creditHour = $request->credit_hour;

        // Check if course existed
        if (Course::where('code', $code)->first()) {
            Course::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                    'credit_hour' => $creditHour,
                ],
            ], ['code'], ['code', 'name', 'credit_hour']);
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
}
