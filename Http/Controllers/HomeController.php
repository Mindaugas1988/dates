<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\User;

class HomeController extends Controller
{
    //

    public function login(){

       $users = User::has('photos')->inRandomOrder()->take(10)->get();

       return view('welcome', compact('users'));
    	
    }


    public function register(){

    	$users = User::has('photos')->inRandomOrder()->take(10)->get();

    	return view('registration',compact('users'));
	
    }
}
