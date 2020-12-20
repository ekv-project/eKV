<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    protected $systemSettings;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);;
    }
    public function view(){
        return view('dashboard.admin.system.system')->with(['settings' => $this->systemSettings, 'page' => 'Sistem']);
    }
}
