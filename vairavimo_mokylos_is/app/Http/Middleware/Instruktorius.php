<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Instruktorius
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
         if ( Auth::check() && Auth::user()->isPraktinisInstruktorius() )
        {
            return $next($request);
        }else if(Auth::check() &&  !Auth::user()->isPraktinisInstruktorius() ){
        	Auth::logout();     
        }
        return redirect('/login')->withErrors(['bad_auth'=>'Norėdami matyti praktinio vairavimo instruktoriaus sąsają privalote prisjungti']);
    }
}
