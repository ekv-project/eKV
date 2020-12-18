<?php

namespace App\Http\Controllers\Classroom;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassroomController extends Controller
{
    public function classroom(){
            // If authenticated user is a student or coordinator of a classroom, they will be redirected to their own classroom.
        if(Auth::user()){

        }else{
            // Else redirect to dashboard.
            redirect()->route('dashboard');
        }
    }
    public function view($classroomID){
        // Check if classroom exist. If true, return view
        if(User::where('id', '=', $classroomID)->count() > 0){
            // Only admin, superadmin and student + coordinator in the classroom can view it.
            if(Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')){
                
            }else{
                abort(403, 'Anda tiada akses pada laman ini!');
            }
        }else{
        // Check if classroom exist. Else, abort with 404.
            abort(404, 'Tiada kelas dijumpai!');
        }
    }
    public function update(){
        
    }
    public function addStudent(){

    }
}
