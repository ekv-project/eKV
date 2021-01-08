<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\InstituteSetting;
use Illuminate\Http\Request;

class ExamController extends Controller
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
    
    public function examView(){
        return view('dashboard.exam.view')->with(['settings' => $this->instituteSettings,'page' => 'Penilaian']);
    }
    public function transcriptView(){
        $instituteSettings = InstituteSetting::find(1);
        return view('dashboard.exam.transcript')->with(['settings' => $instituteSettings,'page' => 'Transkrip Penilaian']);
    }
    public function transcriptDownload(){

    }
}
