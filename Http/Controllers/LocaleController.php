<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Illuminate\Support\Facades\Auth;
use App\Locale;


class LocaleController extends Controller
{
    //

    public function guest(Request $request){

      $time = 1000000;
      Cookie::queue('locale_guest',$request->lang, $time);

    	//session(['locale_guest' => $request->lang]);

      return true;

    }


    public function login(Request $request){
    	
          $locale = Locale::updateOrCreate(
          ['user_id' => Auth::user()->id],
          ['user_id'=>Auth::user()->id,'value'=>$request->lang]
          );

          return true;
          
         
    }
}
