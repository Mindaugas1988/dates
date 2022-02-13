<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class RemainingController extends Controller
{
    //

    public function get($date){

      return (60*60*24) - Carbon::now()->diffInSeconds($date);

    }

    public function remaining($date){

    	if ((60*60*24) - Carbon::now()->diffInSeconds($date)< 60 && (60*60*24) - Carbon::now()->diffInSeconds($date) >0) {
    		$result = (60*60*24) - Carbon::now()->diffInSeconds($date);
            return "Liko ". $result. " Sekundes";
    	}


    	if ((60*24)-Carbon::now()->diffInMinutes($date)< 60 && (60*24)-Carbon::now()->diffInMinutes($date) > 0) {
    		$result = (60*24) - Carbon::now()->diffInMinutes($date);
            return "Liko ". $result. " Minutes";
    	}


    	if (24-Carbon::now()->diffInHours($date)>1) {
    		$result = 24 - Carbon::now()->diffInHours($date);
            return "Liko ". $result. " Valandos";
    	}


    	if ((60*60*24) - Carbon::now()->diffInSeconds($date)<=0) {
 
            return "Dvikova baigesi";
    	}
    	
          
    }
}
