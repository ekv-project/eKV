<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public $systemSettings;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);
    }
    public function exam(){
        return view('dashboard.exam.exam')->with(['settings' => $this->systemSettings,'page' => 'Penilaian']);
    }
    public function transcriptView(){
        $systemSettings = SystemSetting::find(1);
        return view('dashboard.exam.transcript')->with(['settings' => $systemSettings,'page' => 'Transkrip Penilaian']);
    }
    public function transcriptDownload(){

    }
}
