<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use netpioneer\authplus\Classes\AuthPlusLogics;
use netpioneer\authplus\Classes\Interfaces\AuthPlusRedirectAuthenticated;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $res = app(AuthPlusRedirectAuthenticated::class)->RedirectHandle($guards);
        if($res != null){
            return $res;
        }
        //die(0);
        return $next($request);
    }
}
