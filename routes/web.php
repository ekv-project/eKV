<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Classroom\ClassroomController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\InstituteSettingController;
use App\Models\InstituteSetting;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('dashboard');
    }else{
        return view('login');
    }
})->name('login');

// User Login and Logout
Route::post('/', [UserController::class, 'login']);
Route::post('/dashboard/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * Dashboard
 */

Route::get('/dashboard', function () { 
    $instituteSettings = InstituteSetting::find(1);
    return view('dashboard.dashboard')->with(['page' => 'Dashboard', 'settings' => $instituteSettings]);
})->name('dashboard')->middleware('auth');

/**
 *  User Profile
 */
Route::get('/dashboard/profile', [UserProfileController::class, 'viewProfile'])->name('profile')->middleware('auth');
Route::get('/dashboard/profile/{username}', [UserProfileController::class, 'view'])->name('profile.user')->middleware('auth');
Route::get('/dashboard/profile/update/{username}', [UserProfileController::class, 'updateView'])->name('profile.update')->middleware('auth');
Route::post('/dashboard/profile/update/{username}', [UserProfileController::class, 'update'])->middleware('auth');
Route::get('/dashboard/profile/download/{username}', [UserProfileController::class, 'download'])->name('profile.download')->middleware('auth');

/**
 *  Student Classroom
 */
Route::get('/dashboard/classroom', [ClassroomController::class, 'classroom'])->name('classroom')->middleware('auth');
Route::get('/dashboard/classroom/{classroomID}', [ClassroomController::class, 'view'])->name('classroom.view')->middleware('auth');
Route::get('/dashboard/classroom/student/{classroomID}', [ClassroomController::class, 'student'])->name('classroom.student')->middleware(['auth']);
Route::get('/dashboard/classroom/update/{classroomID}', [ClassroomController::class, 'update'])->name('classroom.update')->middleware(['auth']);
Route::post('/dashboard/classroom/student/{classroomID}', [ClassroomController::class, 'studentUpdate'])->middleware(['auth']);
Route::post('/dashboard/classroom/update/{classroomID}', [ClassroomController::class, 'classroomUpdate'])->middleware(['auth']);

/**
*   Exam Transcript  
*/
Route::get('/dashboard/exam/transcript/{studentID}', [ExamController::class, 'semesterView'])->name('exam')->middleware('auth');
Route::get('/dashboard/exam/transcript/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptView'])->name('transcript')->middleware('auth');
Route::get('/dashboard/exam/transcript/add/{studentID}', [ExamController::class, 'transcriptAddView'])->name('transcript.add')->middleware('auth');
Route::get('/dashboard/exam/transcript/update/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptUpdateView'])->name('transcript.update')->middleware('auth');
Route::post('/dashboard/exam/transcript/{studentID}', [ExamController::class, 'transcriptRemove'])->middleware('auth');
Route::post('/dashboard/exam/transcript/add/{studentID}', [ExamController::class, 'transcriptAddUpdate'])->middleware('auth');
Route::post('/dashboard/exam/transcript/update/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptAddUpdate'])->middleware('auth');
Route::post('/dashboard/exam/transcript/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptView'])->middleware('auth');

/** 
 *  Administration Area
 */

// View: List all users. This page have a search bar for finding user that an admin want to change (update info or delete the user)
Route::get('/dashboard/admin/user',[UserController::class,'adminUserView'])->name('admin.user')->middleware(['auth','userIsAdmin']);
// View: Add new users
Route::get('/dashboard/admin/user/add',[UserController::class, 'adminAddUserView'])->name('admin.user_add')->middleware(['auth', 'userIsAdmin']);
// View: User update (update info or delete the user)
Route::get('/dashboard/admin/user/update',[UserController::class, 'adminUpdateUserView'])->name('admin.user_update')->middleware(['auth', 'userIsAdmin']);
// POST requests
Route::post('/dashboard/admin/user/add',[UserController::class, 'adminAddUser'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/user/update',[UserController::class, 'adminUpdateUser'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/institute',[InstituteSettingController::class, 'view'])->name('admin.institute')->middleware(['auth', 'userIsSuperAdmin']);
Route::get('/dashboard/admin/institute/update',[InstituteSettingController::class, 'updateView'])->name('admin.institute.update')->middleware(['auth', 'userIsSuperAdmin']);
Route::post('/dashboard/admin/institute/update',[InstituteSettingController::class, 'updateSettings'])->middleware(['auth', 'userIsSuperAdmin']);