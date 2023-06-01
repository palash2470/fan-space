<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserMiddleware
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
        if (empty(Auth::user()->id)) {
            return redirect()->to('/');
        } else if (Auth::user()->role != 2 && Auth::user()->role != 3) {
            return redirect()->to('/');
        }

        // if($request->input('role')=='admin' || $request->input('role')=='user'){
        //     return redirect()->back()->with('success','user created successfully');
        // }
        return $next($request);
    }
}
