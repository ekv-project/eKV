<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserProfileController;
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
    return view('dashboard.dashboard');
})->name('dashboard')->middleware('auth');

/**
 * User Profile
 */
Route::get('/dashboard/user/profile', [UserProfileController::class, 'viewProfile'])->name('profile')->middleware('auth');
Route::get('/dashboard/user/profile/{username}', [UserProfileController::class, 'view'])->name('profile.user')->middleware('auth');
Route::get('/dashboard/user/profile/{username}/update', [UserProfileController::class, 'updateView'])->name('profile.user_update')->middleware('auth');
Route::post('/dashboard/user/profile/{username}/update', [UserProfileController::class, 'update'])->middleware('auth');
Route::get('/dashboard/user/profile/{username}/download', [UserProfileController::class, 'download'])->middleware('auth');

/** 
 * Administration Area
 */

 // User 
    // View user list
Route::get('/dashboard/admin/user', function () {
})->middleware(['auth','userIsAdmin']);

    // Add new user view
Route::get('/dashboard/admin/user/add', function () {
    return view('dashboard.admin.user.user');
})->name('admin.user_add')->middleware(['auth', 'userIsSuperAdmin']);

    // Add new user controller 
Route::post('/dashboard/admin/user/add',[UserController::class, 'addNewUser'])->middleware(['auth', 'userIsSuperAdmin']);
