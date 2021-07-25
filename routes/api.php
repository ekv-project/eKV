<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\LiveSearch\LiveSearchController;

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

/**
 * The API endpoints is protected with Laravel Sanctum
 */

// Route::group(['middleware' => ['auth:sanctum']], function (){

// });

/**
 * Display the total count of login statistics
 */

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('/statistic/login/all', [StatisticController::class, 'showLoginStatisticsAllCount']);
    Route::get('/statistic/login/day', [StatisticController::class, 'showLoginStatisticsByDayCount']);
    Route::get('/statistic/login/month', [StatisticController::class, 'showLoginStatisticsByMonthCount']);
    Route::get('/statistic/login/year', [StatisticController::class, 'showLoginStatisticsByYearCount']);
});

/**
 * Display the total count of login statistics by role
 */

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::get('/statistic/login/all/role/{role}', [StatisticController::class, 'showLoginStatisticsAllRoleCount']);
    Route::get('/statistic/login/day/role/{role}', [StatisticController::class, 'showLoginStatisticsByDayRoleCount']);
    Route::get('/statistic/login/month/role/{role}', [StatisticController::class, 'showLoginStatisticsByMonthRoleCount']);
    Route::get('/statistic/login/year/role/{role}', [StatisticController::class, 'showLoginStatisticsByYearRoleCount']);
});

//Route::middleware('auth:api')->get('/search', [LiveSearchController::class, 'search']);
//Route::middleware('auth:api')->get('/search', [LiveSearchController::class, 'search']);
// Route::get('/search', [LiveSearchController::class, 'search']);
