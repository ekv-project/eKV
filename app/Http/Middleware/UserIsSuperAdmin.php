<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Gate::allows('authSuperAdmin', Auth::user()->username)) {
            return $next($request);
        } elseif (Gate::denies('authSuperAdmin', Auth::user()->username)) {
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
}
