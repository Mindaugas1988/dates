<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    //
    protected $fillable = ['user_id','block_id'];

    public function user()
    {
        return $this->belongsTo('App\User','block_id');
    }
}
