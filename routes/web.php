<?php

use App\Models\InstituteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\InstallationController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\StudyLevelController;
use App\Http\Controllers\InstituteSettingController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Classroom\ClassroomController;
use App\Http\Controllers\LiveSearch\LiveSearchController;
use App\Http\Controllers\Admin\ClassroomController as AdminClassroomController;

/**
 * API Routes
 * 
 * API routes is added here to be able to authorize API request using CSRF Tokens from Authorization header.
 * I know this is not the right way to do it, but for my implementation, it should be enough.
 * I'm trying my best to follow RFC7235 or any other specs but my knowledge and time is limited.
 */
Route::get('/api/search/{data}/{dataType}', [LiveSearchController::class, 'search']);

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

/**
 * Installation
 */
Route::get('/install', [InstallationController::class, 'installView'])->name('install.view');
Route::get('/install/config', [InstallationController::class, 'installConfigView'])->name('install.config');
Route::post('/install/config', [InstallationController::class, 'install']);
Route::get('/install/success', [InstallationController::class, 'installSuccessView'])->name('install.success');

/**
 * Home
 */
Route::get('/', [LoginController::class, 'rootPage'])->name('login');

// User Login and Logout
Route::post('/', [UserController::class, 'login']);
Route::post('/dashboard/logout', [UserController::class, 'logout'])->name('logout')->middleware(['auth']);

/**
 * Dashboard
 */

Route::get('/dashboard', function () { 
    $instituteSettings = InstituteSetting::find(1);
    return view('dashboard.dashboard')->with(['page' => 'Dashboard', 'settings' => $instituteSettings]);
})->name('dashboard')->middleware(['auth']);

/**
 *  User Profile
 */
Route::get('/dashboard/profile', [UserProfileController::class, 'viewProfile'])->name('profile')->middleware(['auth']);
Route::get('/dashboard/profile/{username}', [UserProfileController::class, 'view'])->name('profile.user')->middleware(['auth']);
Route::get('/dashboard/profile/update/{username}', [UserProfileController::class, 'updateView'])->name('profile.update')->middleware(['auth']);
Route::get('/dashboard/profile/download/{username}', [UserProfileController::class, 'download'])->name('profile.download')->middleware(['auth']);
Route::post('/dashboard/profile/update/{username}', [UserProfileController::class, 'update'])->middleware(['auth']);

/**
 *  Student Classroom
 */
Route::get('/dashboard/classroom', [ClassroomController::class, 'classroom'])->name('classroom')->middleware(['auth']);
Route::get('/dashboard/classroom/{classroomID}', [ClassroomController::class, 'view'])->name('classroom.view')->middleware(['auth']);
Route::get('/dashboard/classroom/student/{classroomID}', [ClassroomController::class, 'student'])->name('classroom.student')->middleware(['auth']);
Route::get('/dashboard/classroom/update/{classroomID}', [ClassroomController::class, 'update'])->name('classroom.update')->middleware(['auth']);
Route::post('/dashboard/classroom/student/{classroomID}', [ClassroomController::class, 'studentUpdate'])->middleware(['auth']);
Route::post('/dashboard/classroom/update/{classroomID}', [ClassroomController::class, 'classroomUpdate'])->middleware(['auth']);

/**
*   Exam Transcript  
*/
    // Exam Transcript Excel Template
Route::get('/dashboard/exam/transcript/template', [ExamController::class, 'downloadSpreadsheetTemplate'])->name('transcript.template')->middleware(['auth']);
    // ETC
Route::get('/dashboard/exam/transcript', [ExamController::class, 'transcript'])->name('transcript')->middleware(['auth']);
Route::get('/dashboard/exam/transcript/{studentID}', [ExamController::class, 'semesterView'])->name('transcript.student')->middleware(['auth']);
Route::get('/dashboard/exam/transcript/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptView'])->name('transcript.view')->middleware(['auth']);
Route::get('/dashboard/exam/transcript/add/{studentID}', [ExamController::class, 'transcriptAddView'])->name('transcript.add')->middleware('auth');
Route::get('/dashboard/exam/transcript/update/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptUpdateView'])->name('transcript.update')->middleware(['auth']);
Route::get('/dashboard/exam/transcript/download/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptDownload'])->name('transcript.download')->middleware(['auth']);
Route::post('/dashboard/exam/transcript', [ExamController::class, 'transcriptBulkAdd'])->middleware(['auth']);
Route::post('/dashboard/exam/transcript/{studentID}', [ExamController::class, 'transcriptRemove'])->middleware(['auth']);
Route::post('/dashboard/exam/transcript/add/{studentID}', [ExamController::class, 'transcriptAddUpdate'])->middleware(['auth']);
Route::post('/dashboard/exam/transcript/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptView'])->middleware(['auth']);
Route::post('/dashboard/exam/transcript/update/{studentID}/{studyLevel}/{semester}', [ExamController::class, 'transcriptAddUpdate'])->middleware(['auth']);

/** 
 *  Administration Area
 */

Route::get('/dashboard/admin', [AdminController::class, 'view'])->name('admin')->middleware(['auth','userIsAdmin']);

Route::get('/dashboard/admin/user',[UserController::class, 'adminUserView'])->name('admin.user')->middleware(['auth','userIsAdmin']);
Route::get('/dashboard/admin/user/add',[UserController::class, 'adminAddUserView'])->name('admin.user.add')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/user/update/{username}',[UserController::class, 'adminUpdateUserView'])->name('admin.user.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/user',[UserController::class, 'remove'])->middleware(['auth','userIsAdmin']);
Route::post('/dashboard/admin/user/add',[UserController::class, 'adminAddUser'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/user/update/{username}',[UserController::class, 'adminUpdateUser'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/course',[CourseController::class, 'view'])->name('admin.course')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/course/add',[CourseController::class, 'addView'])->name('admin.course.add')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/course/update/{code}',[CourseController::class, 'updateView'])->name('admin.course.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/course/add',[CourseController::class, 'add'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/course/update/{code}',[CourseController::class, 'update'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/course',[CourseController::class, 'remove'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/program',[ProgramController::class, 'view'])->name('admin.program')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/program/add',[ProgramController::class, 'addView'])->name('admin.program.add')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/program/update/{code}',[ProgramController::class, 'updateView'])->name('admin.program.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/program/add',[ProgramController::class, 'add'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/program/update/{code}',[ProgramController::class, 'update'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/program',[ProgramController::class, 'remove'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/studylevel',[StudyLevelController::class, 'view'])->name('admin.studylevel')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/studylevel/add',[StudyLevelController::class, 'addView'])->name('admin.studylevel.add')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/studylevel/update/{code}',[StudyLevelController::class, 'updateView'])->name('admin.studylevel.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/studylevel/add',[StudyLevelController::class, 'add'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/studylevel/update/{code}',[StudyLevelController::class, 'update'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/studylevel',[StudyLevelController::class, 'remove'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/classroom',[AdminClassroomController::class, 'view'])->name('admin.classroom')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/classroom/add',[AdminClassroomController::class, 'addView'])->name('admin.classroom.add')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/classroom/update/{id}',[AdminClassroomController::class, 'updateView'])->name('admin.classroom.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/classroom/add',[AdminClassroomController::class, 'add'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/classroom/update/{id}',[AdminClassroomController::class, 'update'])->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/classroom',[AdminClassroomController::class, 'remove'])->middleware(['auth', 'userIsAdmin']);

Route::get('/dashboard/admin/institute',[InstituteSettingController::class, 'view'])->name('admin.institute')->middleware(['auth', 'userIsAdmin']);
Route::get('/dashboard/admin/institute/update',[InstituteSettingController::class, 'updateView'])->name('admin.institute.update')->middleware(['auth', 'userIsAdmin']);
Route::post('/dashboard/admin/institute/update',[InstituteSettingController::class, 'updateSettings'])->middleware(['auth', 'userIsAdmin']);