<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Validator;

class TodoValidation
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
            'title'        => 'required',
            'card_todo_id' => 'required|exists:card_todos',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return $validator->errors();
        }
    }

}
