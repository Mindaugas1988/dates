<?php

namespace App\Rules;
use App;
use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Contracts\Validation\Rule;


class UpdateEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $exists = User::where('id', '<>' ,Auth::id())->
        where('email', '=' ,$value)->exists();

        if ($exists) {
            return false;
        }else{
            return true;
        }
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('words.update_email');
    }
}
