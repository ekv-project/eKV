<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\MainController;

class AdminController extends MainController
{
    /**
     * Handling Views.
     */
    public function view()
    {
        return view('dashboard.admin.view')->with(['settings' => $this->instituteSettings, 'page' => 'Pentadbir']);
    }
}
