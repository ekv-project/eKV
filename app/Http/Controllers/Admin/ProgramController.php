<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\MainController;

class ProgramController extends MainController
{
    /**
     * Handling Views.
     */
    public function view(Request $request)
    {
        $pagination = 15;
        $program = Program::paginate($pagination)->withQueryString();
        // Check for filters and search
        if ($request->has('sort_by') and $request->has('sort_order') and $request->has('search')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order;
            $search = $request->search;
            if (null != $search) {
                $program = Program::where('code', 'LIKE', "%{$search}%")->orWhere('name', 'LIKE', "%{$search}%")->orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            } else {
                $program = Program::orderBy($sortBy, $sortOrder)->paginate($pagination)->withQueryString();
            }
            $filterAndSearch = [
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'search' => $search,
            ];

            return view('dashboard.admin.program.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Program', 'program' => $program, 'filterAndSearch' => $filterAndSearch]);
        } else {
            return view('dashboard.admin.program.view')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Program', 'program' => $program]);
        }
    }

    public function addView()
    {
        return view('dashboard.admin.program.add')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Program']);
    }

    public function updateView($code)
    {
        if (Program::where('code', $code)->first()) {
            $program = Program::where('code', $code)->first();

            return view('dashboard.admin.program.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Program', 'program' => $program]);
        } else {
            abort(404, 'Program tidak dijumpai');
        }
    }

    /**
     * Handling POST Request.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'program_code' => ['required'],
            'program_name' => ['required'],
        ]);
        $code = $request->program_code;
        $name = $request->program_name;
        // Check if program existed
        if (!Program::where('code', $code)->first()) {
            Program::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                ],
            ], ['code'], ['code', 'name']);
            session()->flash('programAddSuccess', 'Program berjaya ditambah!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'existed' => 'Program telah wujud!',
            ]);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'program_code' => ['required'],
            'program_name' => ['required'],
        ]);
        $code = $request->program_code;
        $name = $request->program_name;
        // Check if course existed
        if (Program::where('code', $code)->first()) {
            Program::upsert([
                [
                    'code' => strtolower($code),
                    'name' => strtolower($name),
                ],
            ], ['code'], ['code', 'name']);
            session()->flash('programUpdateSuccess', 'Program berjaya dikemas kini!');

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors([
                'notExisted' => 'Program tidak wujud!',
            ]);
        }
    }

    public function remove(Request $request)
    {
        if (isset($request->code)) {
            $code = $request->code;
            if (Program::where('code', $code)->first()) {
                Program::where('code', $code)->delete();
                session()->flash('deleteSuccess', 'Program berjaya dibuang!');

                return redirect()->back();
            }
        }
    }
}
