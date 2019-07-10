<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Suivi
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
        if (Auth::check() && Auth::user()->acces_suivi == 1) {
            return $next($request);
        }
        return redirect('home');
    }
}
