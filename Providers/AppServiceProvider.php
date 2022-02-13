<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

         view()->composer('*', function ($view) 
   {

        if (Auth::check()) {
            $id = Auth::user()->id;
            $master_username = Auth::user()->name;
         if (User::find($id)->photos->first() === null) {
            $master_photo = Auth::user()->primary_photo;
         }else{
            $master_photo =User::find($id)->photos->first()->photo;
         }
         View::share('master_photo',$master_photo);
         View::share('master_id',$id);
         View::share('master_username',$master_username);
        }
         
    }
); 

         

        
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
