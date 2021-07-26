<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;

class StatisticController extends MainController
{
    /**
     * Handling Views
     */
    public function view(Request $request){
        if ($request->session()->has('bearerAPIToken')) {
            $apiToken = $request->session()->get('bearerAPIToken');
        }else{
            $apiToken = NULL;
        }
        return view('dashboard.admin.statistic.view')->with(['settings' => $this->instituteSettings, 'page' => 'Statistik', 'apiToken' => $apiToken]);
    }
}
