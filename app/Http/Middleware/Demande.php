<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Demande
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
        if (Auth::check() && Auth::user()->acces_demande == 1) {
            return $next($request);
        }
        return redirect('home');
    }
}
