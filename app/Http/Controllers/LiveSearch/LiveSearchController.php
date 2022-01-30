<?php

namespace App\Http\Controllers\LiveSearch;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LiveSearchController extends Controller
{
    // public function search(Request $request, $data, $dataType){
    //     if(isset($request->data) & isset($request->dataType)){
    //         /**
    //          * Token fetched from request Authorization header. The token is store in the HTML meta tag then passed to the
    //          * request header.
    //          */
    //         $apiToken = $request->header('Authorization');
    //         $token = $request->session()->token();
    //         /**
    //          * Check if given apiToken is the same as the current CSRF token.
    //          */
    //         if($apiToken == $token){
    //             if($request->data != ""){
    //                 if($request->dataType == "user"){
    //                     $response = User::where("username", "LIKE", "%{$request->data}%")
    //                     ->orWhere("fullname", "LIKE", "%{$request->data}%")
    //                     ->orWhere("email", "LIKE", "%{$request->data}%")
    //                     ->select("username", "fullname", "email")->get();
    //                     header("Content-Type: application/json");
    //                     return response()->json($response);
    //                 }
    //                 if($request->dataType == "student"){
    //                     // Only user with the student role is retrieved
    //                     $response = User::select("username", "fullname", "email")
    //                     ->where("role", "student")
    //                     ->where("username", "LIKE", "%{$request->data}%")
    //                     ->where("fullname", "LIKE", "%{$request->data}%")
    //                     ->where("email", "LIKE", "%{$request->data}%")->get();
    //                     header("Content-Type: application/json");
    //                     return response()->json($response);
    //                 }
    //                 if($request->dataType == "lecturer"){
    //                     // Only user with the lecturer role is retrieved
    //                     $response = User::select("username", "fullname", "email")
    //                     ->where("role", "lecturer")
    //                     ->where("username", "LIKE", "%{$request->data}%")
    //                     ->where("fullname", "LIKE", "%{$request->data}%")
    //                     ->where("email", "LIKE", "%{$request->data}%")->get();
    //                     header("Content-Type: application/json");
    //                     return response()->json($response);
    //                 }
    //                 if($request->dataType == "classroom"){
    //                     $response = Classroom::where("id", "LIKE", "%{$request->data}%")
    //                     ->orWhere("programs_code", "LIKE", "%{$request->data}%")
    //                     ->orWhere("admission_year", "LIKE", "%{$request->data}%")
    //                     ->orWhere("study_year", "LIKE", "%{$request->data}%")
    //                     ->orWhere("study_levels_code", "LIKE", "%{$request->data}%")
    //                     ->select("id", "programs_code", "admission_year", "study_year", "study_levels_code")->get();
    //                     header("Content-Type: application/json");
    //                     return response()->json($response);
    //                 }
    //             }else{
    //                 $error = [
    //                     'emptyErr' => 'Error: Query is empty!'
    //                 ];
    //                 return response()->json($error);
    //             }
    //         }

    //     }
    // }
}
