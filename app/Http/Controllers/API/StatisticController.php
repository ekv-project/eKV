<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    /**
     * Display the total count of login statistics by role
     */
    public function showLoginStatisticsByDayCount(Request $request){
        $adminActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
        $lecturerActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'lecturer')->get();
        $studentActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'student')->get();
        function checkPeriod($activities){
            $validatedActivities = [];
            foreach ($activities as $key => $value){
                if($value->created_at->format('d') == date('d')){
                    array_push($validatedActivities, $value->id);
                }
            }
            return count($validatedActivities);
        }
        return response()->json([
            'time_period' => 'day',
            'total' => checkPeriod($adminActivities) + checkPeriod($lecturerActivities) + checkPeriod($studentActivities),
            'admin' => checkPeriod($adminActivities),
            'lecturer' => checkPeriod($lecturerActivities),
            'student' => checkPeriod($studentActivities),
        ]);
    }

    public function showLoginStatisticsByMonthCount(Request $request){
        $adminActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
        $lecturerActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'lecturer')->get();
        $studentActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'student')->get();
        function checkPeriod($activities){
            $validatedActivities = [];
            foreach ($activities as $key => $value){
                if($value->created_at->format('m') == date('m')){
                    array_push($validatedActivities, $value->id);
                }
            }
            return count($validatedActivities);
        }
        return response()->json([
            'time_period' => 'month',
            'total' => checkPeriod($adminActivities) + checkPeriod($lecturerActivities) + checkPeriod($studentActivities),
            'admin' => checkPeriod($adminActivities),
            'lecturer' => checkPeriod($lecturerActivities),
            'student' => checkPeriod($studentActivities),
        ]);
    }

    public function showLoginStatisticsByYearCount(Request $request){
        $adminActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
        $lecturerActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'lecturer')->get();
        $studentActivities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'student')->get();
        function checkPeriod($activities){
            $validatedActivities = [];
            foreach ($activities as $key => $value){
                if($value->created_at->format('Y') == date('Y')){
                    array_push($validatedActivities, $value->id);
                }
            }
            return count($validatedActivities);
        }
        return response()->json([
            'time_period' => 'year',
            'total' => checkPeriod($adminActivities) + checkPeriod($lecturerActivities) + checkPeriod($studentActivities),
            'admin' => checkPeriod($adminActivities),
            'lecturer' => checkPeriod($lecturerActivities),
            'student' => checkPeriod($studentActivities),
        ]);
    }
    // public function showLoginStatisticsAllRoleCount(Request $request, $role){
    //     $role = strtolower($role);
    //     $count = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username')->where('users.role', $role)->count();
    //     return response()->json([
    //         'type' => 'role',
    //         'group_by' => 'none',
    //         'role' => $role,
    //         'count' => $count,
    //     ]);
    // }

    // public function showLoginStatisticsByDayRoleCount(Request $request, $role){
    //     $role = strtolower($role);
    //     $currentDay = date('d');
    //     if($role == 'admin'){
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
    //     }else{
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
    //     }
    //     $allGroupActivities = [];
    //     foreach ($activities as $key => $value){
    //         if($value->created_at->format('d') == $currentDay){
    //             array_push($allGroupActivities, $value->id);
    //         }
    //     }
    //     $count = count($allGroupActivities);
    //     return response()->json([
    //         'type' => 'role',
    //         'group_by' => 'day',
    //         'role' => $role,
    //         'count' => $count,
    //     ]);
    // }

    // public function showLoginStatisticsByWeekRoleCount(Request $request, $role){
    //     $role = strtolower($role);
    //     // Week is so hard
    //     if($role == 'admin'){
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
    //     }else{
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
    //     }
    //     $allGroupActivities = [];
    //     foreach ($activities as $key => $value){
    //         if($value->created_at->format('d') == $currentDay){
    //             array_push($allGroupActivities, $value->id);
    //         }
    //     }
    //     $count = count($allGroupActivities);
    //     return response()->json([
    //         'type' => 'role',
    //         'group_by' => 'day',
    //         'role' => $role,
    //         'count' => $count,
    //     ]);
    // }
    // public function showLoginStatisticsByMonthRoleCount(Request $request, $role){
    //     $role = strtolower($role);
    //     $currentMonth = date('m');
    //     if($role == 'admin'){
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
    //     }else{
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
    //     }
    //     $allGroupActivities = [];
    //     foreach ($activities as $key => $value){
    //         if($value->created_at->format('m') == $currentMonth){
    //             array_push($allGroupActivities, $value->id);
    //         }
    //     }
    //     $count = count($allGroupActivities);
    //     return response()->json([
    //         'type' => 'role',
    //         'group_by' => 'month',
    //         'role' => $role,
    //         'count' => $count,
    //     ]);
    // }

    // public function showLoginStatisticsByYearRoleCount(Request $request, $role){
    //     $role = strtolower($role);
    //     $currentYear = date('Y');
    //     if($role == 'admin'){
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', 'admin')->orWhere('users.role', 'superadmin')->get();
    //     }else{
    //         $activities = User::join('login_activities', 'users.username', '=', 'login_activities.users_username')->select('users_username', 'login_activities.created_at')->where('users.role', $role)->get();
    //     }
    //     $allGroupActivities = [];
    //     foreach ($activities as $key => $value){
    //         if($value->created_at->format('Y') == $currentYear){
    //             array_push($allGroupActivities, $value->id);
    //         }
    //     }
    //     $count = count($allGroupActivities);
    //     return response()->json([
    //         'type' => 'role',
    //         'group_by' => 'day',
    //         'role' => $role,
    //         'count' => $count,
    //     ]);
    // }   
}
