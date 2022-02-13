<?php

namespace App\Http\Middleware;

use Closure;
use App\Visitor;
use App\User;
use App\Notifications\Visitors as Visiting;
use Illuminate\Support\Facades\Auth;

class Visitors
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
        $response = $next($request);

        // Perform action

        if (Auth::user()->id != $request->id) {
            
         Visitor::Create(
            ['user_id' => $request->id,'visitor_id'=> Auth::user()->id]
          );
         User::find($request->id)->notify(new Visiting());

        }
        

        return $response;
    }
}
