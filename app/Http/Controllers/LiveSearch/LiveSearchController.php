<?php

namespace App\Http\Controllers\LiveSearch;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use App\Models\InstituteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class LiveSearchController extends Controller
{
    //Note: Improve the API by adding authentication
    public function search(Request $request){
        if(isset($request->data) & isset($request->dataType)){
            /**
             * Token fetched from request header. The token is store in the HTML meta tag then passed to the
             * request header. This token is fetched from the 
             */
            $headerToken = $request->header("API-TOKEN");
            /**
             * Check for API token in database
             */
            $tokenCount = User::where("api_token", $headerToken)->count();
            /**
             * Check if there"s specified token in database.
             */
            if($tokenCount > 0){
                if(!empty($request->data)){
                    if($request->dataType == "user"){
                        $response = User::where("username", "LIKE", "%{$request->data}%")
                        ->orWhere("fullname", "LIKE", "%{$request->data}%")
                        ->orWhere("email", "LIKE", "%{$request->data}%")
                        ->select("username", "fullname", "email")->get();
                        header("Content-Type: application/json");
                        return response()->json($response);
                    }
                    if($request->dataType == "student"){
                        // Only user with the student role is retrieved
                        $response = User::select("username", "fullname", "email")
                        ->where("role", "student")
                        ->where("username", "LIKE", "%{$request->data}%")
                        ->where("fullname", "LIKE", "%{$request->data}%")
                        ->where("email", "LIKE", "%{$request->data}%")->get();
                        header("Content-Type: application/json");
                        return response()->json($response);
                    }
                    if($request->dataType == "classroom"){
                        $response = Classroom::where("id", "LIKE", "%{$request->data}%")
                        ->orWhere("programs_code", "LIKE", "%{$request->data}%")
                        ->orWhere("admission_year", "LIKE", "%{$request->data}%")
                        ->orWhere("study_year", "LIKE", "%{$request->data}%")
                        ->orWhere("study_levels_code", "LIKE", "%{$request->data}%")
                        ->select("id", "programs_code", "admission_year", "study_year", "study_levels_code")->get();
                        header("Content-Type: application/json");
                        return response()->json($response);
                    }
                }else{
                    $error = ["Error: Query is empty!"];
                    return response()->json($error);
                }   
            }
            
        }
    }
}
