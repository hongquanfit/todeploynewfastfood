<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DetectLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->role_id) {
                return $next($request);
            }
            else{
                if ($request->is('admin/*')) {
                    return redirect('/nothavepermission');
                }
                
                return $next($request);
            }
        }
        
        if ($request->ajax()) {
            echo 'ajax';
            exit;
        }

        return redirect('/login');        
    }
}
