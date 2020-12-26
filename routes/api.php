<?php

use App\Http\Controllers\LiveSearch\LiveSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/search', [LiveSearchController::class, 'search']);
//Route::middleware('auth:api')->get('/search', [LiveSearchController::class, 'search']);
Route::get('/search', [LiveSearchController::class, 'search']);
