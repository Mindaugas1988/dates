<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OnlineActivity
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

       if (Auth::check()) {
           # code...
           $expires = Carbon::now()->addMinutes(120);
           Cache::put('user-online'. Auth::user()->id,true,$expires);

       }

        return $next($request);
    }
}
