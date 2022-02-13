<?php

namespace App\Rules;
use Carbon\Carbon;
use App;

use Illuminate\Contracts\Validation\Rule;

class Adult implements Rule
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
        $year = Carbon::now()->diffInYears($value);

        if ($year>17) {
           return true;
        }
        else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
           
         return trans('words.adult');

    }
}
