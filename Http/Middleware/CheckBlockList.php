<?php

namespace App\Http\Middleware;

use Closure;
use App\Block;
use Illuminate\Support\Facades\Auth;

class CheckBlockList
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

        $result = Block::where('user_id', $request->id)
        ->where('block_id', Auth::user()->id)
        ->exists();


        if ($result) {
          return redirect('sign-in/blocked/'.$request->id.'');
        }

        return $next($request);
    }
}
