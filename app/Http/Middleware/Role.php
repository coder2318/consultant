<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
//        $user = auth()->user();
//        $roles = explode('|', $roles);
//
//        if ($user && (in_array($user->profile?->role, $roles) || in_array($user->consultant?->role, $roles) ||in_array($user->admin?->role, $roles))) {
//            return $next($request);
//        }
//
//        return response()->errorJson('У вас нет доступа к этой странице|403', 403);

        return $next($request);
    }
}
