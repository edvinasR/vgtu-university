<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Mokinys
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if ( Auth::check() && Auth::user()->isMokinys() )
        {
            return $next($request);
        }else if(Auth::check() &&  !Auth::user()->isMokinys() ){
        	Auth::logout();     
        }
        return redirect('/login')->withErrors(['bad_auth'=>'Norėdami matyti mokinio sąsają privalote prisjungti']);
    }
}
