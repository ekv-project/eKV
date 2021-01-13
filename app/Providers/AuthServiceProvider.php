<?php

namespace App\Providers;

use App\Http\Controllers\User\AuthorizationController;
use App\Models\Classroom;
use App\Models\ClassroomCoordinator;
use App\Models\ClassroomStudent;
use App\Models\Coordinator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

         // Check if current user a super admin
         Gate::define('authSuperAdmin', function (User $user){
            return $user->role === 'superadmin';
        });
        // Check if current user an admin
        Gate::define('authAdmin', function (User $user){
            return $user->role === 'admin' || $user->role === 'superadmin';
        });
        // Check if current user allowed to view
        Gate::define('authUser', function (User $user, $id){
            return $user->username === $id;
        });
        // Check if current use is lecturer
        Gate::define('authLecturer', function (User $user){
            return $user->role === 'lecturer';
        });
        // Check if current use is student
        Gate::define('authStudent', function (User $user){
            return $user->role === 'student';
        });
        // Check if current user is coordinator for a user page they trying to access
        Gate::define('authCoordinator', function (User $user, $id){
            $currentUser = $user->username;
            if(!empty(ClassroomStudent::where('users_username', $id)->first()->classroom)){
                $classroomStudentID = ClassroomStudent::where('users_username', $id)->first()->classroom->id;
                if(!empty(ClassroomCoordinator::where('users_username', $currentUser)->first()->classroom)){
                    $classroomCoordinatorID = ClassroomCoordinator::where('users_username', $currentUser)->first()->classroom->id;
                    if($classroomCoordinatorID == $classroomStudentID){
                        return true;
                    }
                }
            }elseif(!empty(ClassroomCoordinator::where('users_username', $currentUser)->first()->classroom)){
                $classroomCoordinatorID = ClassroomCoordinator::where('users_username', $currentUser)->first()->classroom->id;
                if($classroomCoordinatorID == $id){
                    return true;
                }
            }
        });
    }
}
