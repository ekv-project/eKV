<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Coordinator;
use App\Models\ClassroomStudent;
use App\Models\ClassroomCoordinator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        Gate::define('authSuperAdmin', function (User $user) {
            return 'superadmin' === $user->role;
        });
        // Check if current user an admin
        Gate::define('authAdmin', function (User $user) {
            return 'admin' === $user->role || 'superadmin' === $user->role;
        });
        // Check if current user allowed to view
        Gate::define('authUser', function (User $user, $id) {
            return $user->username === $id;
        });
        // Check if current use is lecturer
        Gate::define('authLecturer', function (User $user) {
            return 'lecturer' === $user->role;
        });
        // Check if current use is student
        Gate::define('authStudent', function (User $user) {
            return 'student' === $user->role;
        });
        // Check if current user is coordinator for a user page they trying to access
        Gate::define('authCoordinator', function (User $user, $id) {
            $currentUser = $user->username;
            if (!empty(ClassroomStudent::where('users_username', $id)->first()->classroom)) {
                // Pass student ID
                $studentClassroomID = ClassroomStudent::where('users_username', $id)->first()->classroom->id;
                $currentUserClassroomID = ClassroomCoordinator::where('users_username', $currentUser)->where('classrooms_id', $studentClassroomID)->first();
                if ($currentUserClassroomID) {
                    return true;
                }
            } elseif (ClassroomCoordinator::where('users_username', $currentUser)->get()->count() > 0) {
                // Pass classroom ID
                $classroomCoordinatorID = ClassroomCoordinator::where('users_username', $currentUser)->where('classrooms_id', $id)->first();
                if ($classroomCoordinatorID) {
                    return true;
                }
            }
        });
    }
}
