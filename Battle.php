<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Battle extends Model
{
    //
    protected $fillable = ['user_id','invite_id','content'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
