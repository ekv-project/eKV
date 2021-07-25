<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    /**
     * ToDO: Add auth with Laravel Sanctum. Refer Traversy Media video.
     */
    
    /**
     * Display the total count of login statistics
     */
    public function showLoginStatisticsAllCount(Request $request){
        return "test";
        $count = LoginActivity::all()->count();
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'none',
            'count' => $count,
        ]);
    }

    public function showLoginStatisticsByDayCount(Request $request){
        $currentDay = date('d');
        $activities = LoginActivity::all();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('d') == $currentDay){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'day',
            'count' => $count,
        ]);
    }
    
    public function showLoginStatisticsByMonthCount(Request $request){
        $currentMonth = date('m');
        $activities = LoginActivity::all();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('m') == $currentMonth){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'month',
            'count' => $count,
        ]);
    }

    public function showLoginStatisticsByYearCount(Request $request){
        $currentYear = date('Y');
        $activities = LoginActivity::all();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('Y') == $currentYear){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'year',
            'count' => $count,
        ]);
    }

    /**
     * Display the total count of login statistics by role
     */
    public function showLoginStatisticsAllRoleCount(Request $request, $role){
        $role = strtolower($role);
        $count = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username')->where('users.role', $role)->count();
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'none',
            'role' => $role,
            'count' => $count,
        ]);
    }

    public function showLoginStatisticsByDayRoleCount(Request $request, $role){
        $role = strtolower($role);
        $currentDay = date('d');
        $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('d') == $currentDay){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'day',
            'role' => $role,
            'count' => $count,
        ]);
    }
    
    public function showLoginStatisticsByMonthRoleCount(Request $request, $role){
        $role = strtolower($role);
        $currentMonth = date('m');
        $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('m') == $currentMonth){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'day',
            'role' => $role,
            'count' => $count,
        ]);
    }

    public function showLoginStatisticsByYearRoleCount(Request $request, $role){
        $role = strtolower($role);
        $currentYear = date('Y');
        $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
        $allGroupActivities = [];
        foreach ($activities as $key => $value){
            if($value->created_at->format('Y') == $currentYear){
                array_push($allGroupActivities, $value->id);
            }
        }
        $count = count($allGroupActivities);
        return response()->json([
            'type' => 'show_all',
            'group_by' => 'day',
            'role' => $role,
            'count' => $count,
        ]);
    }   

    /**
     * Display the total count of login statistics using what browser
     */
}
