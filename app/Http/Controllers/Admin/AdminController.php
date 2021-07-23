<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\InstituteSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;

class AdminController extends MainController
{
    /**
     * Handling Views
     */
    public function view(){
        return view('dashboard.admin.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pentadbir']);
    }
}
