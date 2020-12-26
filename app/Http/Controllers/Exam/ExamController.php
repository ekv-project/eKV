<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class ExamController extends Controller
{
        /***************************************************************************
     * Controller Constuctor
     * Most of the properties included here is used by any of the methods below.
     **************************************************************************/
    protected $systemSettings;
    protected $currentUserUsername;
    protected $apiToken;
    public function __construct()
    {
        $this->systemSettings = SystemSetting::find(1);
        $this->middleware(function ($request, $next) {      
            $this->currentUserUsername = 'admin';
            $this->apiToken = User::where('username', $this->currentUserUsername)->select('api_token')->first();
            return $next($request);
        });
    }
    /***************************************************************************/
    
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
