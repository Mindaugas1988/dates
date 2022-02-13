<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    //
    protected $fillable = array('user_id', 'value');
}
