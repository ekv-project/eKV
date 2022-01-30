<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Gate::allows('authAdmin') || Gate::allows('authSuperAdmin')) {
            return $next($request);
        } elseif (Gate::denies('authAdmin') || Gate::denies('authSuperAdmin')) {
            abort(403, 'Anda tiada akses pada laman ini!');
        }
    }
}
