<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstituteSetting;
use App\Models\User;
use Illuminate\Http\Request;

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
    public function view(){
        return view('dashboard.admin.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Senarai Kelas']);
    }
    public function addView(){
        return view('dashboard.admin.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Tambah Kelas']);
    }
    public function updateView(){
        return view('dashboard.admin.classroom.update')->with(['settings' => $this->instituteSettings, 'page' => 'Kemas Kini Kelas']);
    }
    /**
     * Handling POST Request
     */
    public function add(Request $request){

    }
    public function update(Request $request){

    }
    public function remove(Request $request){

    }
}
