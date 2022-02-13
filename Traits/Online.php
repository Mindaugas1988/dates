<?php
namespace App\Traits;
use Illuminate\Support\Facades\Cache;


trait Online{

public function isOnline(){

return Cache::has('user-online'.$this->id);

}


static public function allOnline()
    {
        return self::where('email_verified_at','<>',null)->get()->filter->isOnline();
    }



}
?>