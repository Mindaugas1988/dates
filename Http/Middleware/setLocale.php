<?php

namespace App\Http\Middleware;

use Cookie;
use Closure;
use App;
use Illuminate\Support\Facades\Auth;
use App\Locale;

class setLocale
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
           $locale = Locale::where('user_id',Auth::user()->id)->count();

           if ($locale == 1) {
              $lang = Locale::where('user_id',Auth::user()->id)->value('value');
              App::setLocale($lang);
           }else{

               if (Cookie::get('locale_guest')!== null) {
               App::setLocale(Cookie::get('locale_guest'));
              }else{
               App::setLocale('en');
            }

           }

         }else{

         if (Cookie::get('locale_guest')!== null) {
           App::setLocale(Cookie::get('locale_guest'));
         }else{
            App::setLocale('en');
         }

         }
        
        return $next($request);
    }
}
