<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Validator;

class UserValidation
{    
    /**
     * Method createValidation
     *
     * @param $request $request [explicite description]
     *
     * @return mixed
     */
    public function createValidation($request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:8|confirmed'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }
    }
    
    /**
     * Method loginValidation
     *
     * @param $request $request [explicite description]
     *
     * @return mixed
     */
    public function loginValidation($request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }
    }
}
