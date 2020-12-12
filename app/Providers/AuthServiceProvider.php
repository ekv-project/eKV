<?php

namespace App\Providers;

use App\Http\Controllers\User\AuthorizationController;
use App\Models\Classroom;
use App\Models\Coordinator;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;

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

        // Check if current user allowed to view
        Gate::define('authUser', function (User $user, $username){
            return $user->username === $username;
        });
        // Check if current user an admin
        Gate::define('authAdmin', function (User $user){
            return $user->role === 'admin';
        });
        // Check if current use is lecturer
        Gate::define('authLecturer', function (User $user){
            return $user->role === 'lecturer';
        });
        // Check if current user is coordinator for a user page they trying to access  //In progress 
        Gate::define('authCoordinator', function ($username, Coordinator $coordinator, Classroom $classroom, UserProfile $userProfile){
            return $username;
        });
    }
}
