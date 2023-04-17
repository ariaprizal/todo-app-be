<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Validator;

class CardTodoValidation
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
            'user_id' => 'required|exists:users',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }
    }
}
