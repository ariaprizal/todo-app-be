<?php

namespace App\Http\Helper;

class ResponseHandler
{
    public function dataResponse($success, $message, $data, $code)
    {
        return response()->json([
            'code' => $code,
            'success' => $success,
            'data'    => $data, 
            'error' => $message 
        ], $code);
    }
}
