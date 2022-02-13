<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\CanBeVoted;
use App\User;

class Post_comment extends Model
{
    //

    use CanBeVoted;

    protected $vote = User::class;

    protected $fillable = ['post_id', 'user_id', 'comment'];

     public function post()
    {
        return $this->belongsTo('App\Post');
    }


     public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
