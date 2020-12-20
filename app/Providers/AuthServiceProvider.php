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
            if($user->role === 'superadmin'){
                return true;
            }
        });
        // Check if current user an admin
        Gate::define('authAdmin', function (User $user){
            if($user->role === 'admin'){
                return true;
            }
        });
        // Check if current user allowed to view
        Gate::define('authUser', function (User $user, $username){
            return $user->username === $username;
        });
        // Check if current use is lecturer
        Gate::define('authLecturer', function (User $user){
            return $user->role === 'lecturer';
        });
        // Check if current user is coordinator for a user page they trying to access
        Gate::define('authCoordinator', function (User $user, $username){
            $currentUser = $user->username;
            $studentClassroom = ClassroomStudent::where('users_username', $username)->first()->classroom->id;
            $coordinatorClassroom = ClassroomCoordinator::where('users_username', $currentUser)->first()->classroom->id;
            return $studentClassroom === $coordinatorClassroom;
        });
    }
}
