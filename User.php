<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Online as OnlineTrait;
use Illuminate\Support\Facades\Cache;
use Jcc\LaravelVote\Vote;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use OnlineTrait;
    use Vote;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','gender','location','birth','primary_photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

      public function photos()
    {
        return $this->hasMany('App\Photo');
    }


    public function credits()
    {
        return $this->hasOne('App\Credit');
    }


    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function battles()
    {
        return $this->hasMany('App\Battle');
    }


    public function block_list()
    {
        return $this->hasMany('App\Block','user_id');
    }
    

    public function is_blocked()
    {
        return $this->hasMany('App\Block','block_id');
    }


    public function visitors()
    {
        return $this->hasMany('App\Visitor','user_id');
    }

}
