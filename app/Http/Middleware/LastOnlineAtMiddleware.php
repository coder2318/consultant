<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LastOnlineAtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }
        if(auth()->user()->profile){
            if (auth()->user()->profile->last_online_at->diffInMinutes(now()) >= 1)
            {
                DB::table("profiles")
                    ->where("id", auth()->user()->profile->id)
                    ->update(["last_online_at" => now()]);
            }
        } else {
            Profile::create([
                'user_id' => auth()->id(),
                'role' => Profile::USER_ROLE
            ]);
        }
        return $next($request);
    }
}
