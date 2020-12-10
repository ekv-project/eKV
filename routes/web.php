<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('home');
})->name('login');

// User Login and Logout
Route::post('/', [UserController::class, 'login']);
Route::post('/dashboard/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * Dashboard
 */

Route::get('/dashboard', function () { 
    return view('dashboard.dashboard');
})->middleware('auth');

/** 
 * Administration Area
 */

 // User 

Route::get('/dashboard/admin/user/add', function () {
    return view('dashboard.admin.user.user');
})->name('admin.user_add')->middleware('auth');

Route::post('/dashboard/admin/user/add',[UserController::class, 'addNewUser'])->name('admin.user_add')->middleware('auth');
