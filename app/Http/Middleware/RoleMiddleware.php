<?php

namespace App\Http\Middleware;

use App\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $roles = $role == UserRole::ADMIN->value ? UserRole::adminAccess() : [UserRole::from($role)];
        
        if(!in_array(Auth::user()->role, $roles)){
            abort(403, 'Oops!! Unauthorized Access');
        }

        return $next($request);
    }
}
