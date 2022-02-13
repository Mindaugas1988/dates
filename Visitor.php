<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    //
    protected $fillable = ['user_id','visitor_id'];


     public function user()
    {
        return $this->belongsTo('App\User','visitor_id');
    }
}
