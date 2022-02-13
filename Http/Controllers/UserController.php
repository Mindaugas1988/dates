<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\User;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Rules\Adult;
use App\Rules\UpdateEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;




class UserController extends Controller
{
    //


    public function users(){
      $carbon = new Carbon();
      if (Cookie::get('age1') !== null && Cookie::get('age2') !== null) {
        $age1 = Cookie::get('age1');
        $age2 = Cookie::get('age2');
      }else{
       
        $age1 = 18;
        $age2 = 99;

      }
      $filter_1 = Carbon::now()->subYears($age1)->toDateString();
      $filter_2 = Carbon::now()->subYears($age2)->subDays(364)->subHours(23)->subMinutes(59)->toDateString();   

      $users = User::whereNotNull('email_verified_at')->whereBetween('birth',[$filter_2, $filter_1])
        ->get();

      if (Cookie::get('male') !== null && Cookie::get('female') === null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','male')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') === null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','female')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get();
      }

       if (Cookie::get('male') !== null && Cookie::get('female') === null && Cookie::get('online') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','male')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') === null && Cookie::get('online') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','female')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }


      if (Cookie::get('female') === null && Cookie::get('male') === null && Cookie::get('online') !== null) {
        $users = User::whereNotNull('email_verified_at')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') !== null && Cookie::get('online') !== null) {
        $users = User::whereNotNull('email_verified_at')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }


      //Location


       if (Cookie::get('male') !== null && Cookie::get('female') === null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','male')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') === null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','female')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get();
      }


      if (Cookie::get('male') !== null && Cookie::get('female') === null && Cookie::get('online') !== null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','male')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') === null && Cookie::get('online') !== null  && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')->
        where('gender','female')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }


      if (Cookie::get('female') === null && Cookie::get('male') === null && Cookie::get('online') !== null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }

      if (Cookie::get('female') !== null && Cookie::get('male') !== null && Cookie::get('online') !== null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get()->filter->isOnline();
      }

      if (Cookie::get('female') === null && Cookie::get('male') === null && Cookie::get('online') === null && Cookie::get('location') !== null) {
        $users = User::whereNotNull('email_verified_at')
        ->where('location','like','%'.Cookie::get('location').'%')
        ->whereBetween('birth',[$filter_2, $filter_1])
        ->get();
      }

      $users = $this->custom_paginate($users,10);


    	return view('sign-in.users',compact('users','carbon'));

    }


    public function filter_user(Request $request){

      $time = 1000000;
      Cookie::queue('male',1, -$time);
      Cookie::queue('female',1, -$time);
      Cookie::queue('online',1, -$time);
      Cookie::queue('location',1, -$time);
      Cookie::queue('age1',$request->age1, $time);
      Cookie::queue('age2',$request->age2, $time);

      if (in_array("male",$request->checked)) {
        Cookie::queue('male',1, $time);
      }
      if (in_array("female",$request->checked)) {
        Cookie::queue('female',1, $time);
      }
      if (in_array("online",$request->checked)) {
        Cookie::queue('online',1, $time);
      }
      if (strlen(trim($request->location))>0) {
        Cookie::queue('location',$request->location, $time);
      }

      
      return ['success' => 'Success'];
    }



    public function get_filters(){

      
      if (Cookie::get('age1') !== null && Cookie::get('age2') !== null) {
        $age1 = Cookie::get('age1');
        $age2 = Cookie::get('age2');
      }else{ 
        $age1 = 18;
        $age2 = 99;
      }

      if (Cookie::get('male')!== null) {
        $male = 'male';
      }else{
        $male = null;
      }

      if (Cookie::get('female')!== null) {
        $female = 'female';
      }else{
        $female = null;
      }


      if (Cookie::get('online')!== null) {
        $online = 'online';
      }else{
        $online = null;
      }

      if (Cookie::get('location')!== null) {
        $location = Cookie::get('location');
      }else{
        $location = null;
      }

      return [$age1,$age2,[$male,$female,$online],$location];

    }


    public function profile($name,$id){

      $carbon = new Carbon();

      $user = User::find($id);

      return view('sign-in.profile',compact('user','carbon','id'));

    }



    public function update(Request $request){


       $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255',new UpdateEmail],
            'birth' => ['required', 'date', new Adult],
        ]);

       if ($validator->fails()) {
         return ['error' => $validator->errors()->toArray()];
       }else{

        $user = User::find(Auth::id());
        $user->email = $request->email;
        $user->name = $request->name;
        $user->location = $request->location;
        $user->birth = $request->birth;

        if (strlen($request->password)>5) {
         $user->password = Hash::make($request->password);
        }

        $user->save();

        return ['success' => 'Success'];
       }


    }


    public function delete(Request $request){

        $user = User::find(Auth::id());
        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
          return ['error' => $validator->errors()->toArray()];
        }
        else{
          
           if (Hash::check($request->password, $user->password)) {
               Storage::deleteDirectory('public/galleries/'.Auth::id());
               Auth::user()->notifications()->delete();
               $user->delete();
               return 1;
            }else{
               return ['no_match' => 'Nesutampa slaptazodis'];
            }

        }
    }


    public function all_online(){

        $carbon = new Carbon();

        $users_online = User::allOnline();
        $users_online = $this->custom_paginate($users_online,10);

        return view('sign-in.home',compact('users_online','carbon'));

    }


   public function notification(){

      $duel_count = Auth::user()->unreadNotifications->
      where('type','App\Notifications\NewDual')
      ->count();

      $visitors_count = Auth::user()->unreadNotifications->
      where('type','App\Notifications\Visitors')
      ->count();

      $comments_count = Auth::user()->unreadNotifications->
      where('type','App\Notifications\Comments')
      ->count();



      $duel = ($duel_count > 0 ? $duel_count : null);
      $comment = ($comments_count > 0 ? $comments_count : null);
      $visitor = ($visitors_count > 0 ? $visitors_count : null);

      return [$duel,$comment,$visitor];


   }


          /**
 * @param $items
 * @param $perPage
 * @return \Illuminate\Pagination\LengthAwarePaginator
 */
    private function custom_paginate($items, $perPage){

    $pageStart           = request('page', 1);
    $offSet              = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = $items->slice($offSet, $perPage);

    return new LengthAwarePaginator(
        $itemsForCurrentPage, $items->count(), $perPage,
        Paginator::resolveCurrentPage(),
        ['path' => Paginator::resolveCurrentPath()]
    );

    }

}


