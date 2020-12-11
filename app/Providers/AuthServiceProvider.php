<?php

namespace App\Providers;

use App\Http\Controllers\User\AuthorizationController;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;

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
            return $user->username === 'admin';
        });
        // Check if current use is lecturer
        Gate::define('authLecturer', function (User $user){
            return $user->username === 'lecturer';
        });
        // Check if current use is coordinator for  //In progress 
        Gate::define('authCoordinator', function (User $user, Coordinator $coordinator){
            return $user->username === 'lecturer';
        });
    }
}
