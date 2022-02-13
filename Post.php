<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\CanBeVoted;

class Post extends Model
{
    //

    use CanBeVoted;

    protected $vote = User::class;

    protected $fillable = ['user_id', 'message','type','battle_id'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function enemy()
    {
        return $this->belongsTo('App\User', 'battle_id');
    }


    public function comments()
    {
        return $this->hasMany('App\Post_comment');
    }
}
