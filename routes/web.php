<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserProfileController;
use App\Models\User;
use Illuminate\Support\Facades\App;
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


// Don't forget to add Auth middleware for each routes that needed user to be authenticate to access


// If user not logged in show login page, if logged in redirect to dashboard
Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('dashboard');
    }else{
        return view('home');
    }
})->name('login');

// User Login and Logout
Route::post('/', [UserController::class, 'login']);
Route::post('/dashboard/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * Dashboard
 */

Route::get('/dashboard', function () { 
    return view('dashboard.dashboard')->with(['page' => 'Dashboard']);
})->name('dashboard')->middleware('auth');

/**
 *  Student Classroom
 */
Route::get('/dashboard/classroom', [ClassroomController::class, 'classroom'])->name('classroom')->middleware('auth');
Route::get('/dashboard/classroom/{classroomID}', [ClassroomController::class, 'view'])->name('classroom.view')->middleware('auth');
Route::get('/dashboard/classroom/{classroomID}/update', [ClassroomController::class, 'update'])->name('classroom.update')->middleware(['auth', 'userIsAdmin', 'userIsSuperAdmin']);
Route::get('/dashboard/classroom/{classroomID}/add', [ClassroomController::class, 'addStudent'])->name('classroom.add')->middleware(['auth', 'userIsAdmin', 'userIsSuperAdmin']);


/**
 *  User Profile
 */
Route::get('/dashboard/profile', [UserProfileController::class, 'viewProfile'])->name('profile')->middleware('auth');
Route::get('/dashboard/profile/{username}', [UserProfileController::class, 'view'])->name('profile.user')->middleware('auth');
Route::get('/dashboard/profile/{username}/update', [UserProfileController::class, 'updateView'])->name('profile.update')->middleware('auth');
Route::post('/dashboard/profile/{username}/update', [UserProfileController::class, 'update'])->middleware('auth');
Route::get('/dashboard/profile/{username}/download', [UserProfileController::class, 'download'])->name('profile.download')->middleware('auth');

/** 
 * Administration Area
 */

 // User 
    // View user list
Route::get('/admin/user', function () {
})->middleware(['auth','userIsAdmin']);

    // Add new user view
Route::get('/admin/user/add', function () {
    return view('dashboard.admin.user.user')->with(['page' => 'Tambah Pengguna']);
})->name('admin.user_add')->middleware(['auth', 'userIsSuperAdmin']);

    // Add new user controller 
Route::post('/admin/user/add',[UserController::class, 'addNewUser'])->middleware(['auth', 'userIsSuperAdmin']);
