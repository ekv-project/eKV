<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstituteSetting;
use Illuminate\Http\Request;

class AdminController extends Controller
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
        return view('dashboard.admin.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pentadbir']);
    }
}
