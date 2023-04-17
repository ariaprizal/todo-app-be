<?php

namespace App\Http\Controllers;

use App\Http\Helper\ResponseHandler;
use App\Http\Helper\UserValidation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $userValidation;
    private $responseHandler;
    function __construct(UserValidation $userValidation, ResponseHandler $responseHandler)
    {
        $this->userValidation = $userValidation;
        $this->responseHandler = $responseHandler;
    }

    public function getUser()
    {
        $user = User::all();
        return $this->responseHandler->dataResponse(true, null, $user, 200);
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $validation = $this->userValidation->createValidation($request);

        if($validation != null) {
            return $this->responseHandler->dataResponse(false, $validation->messages(), null, 400 );
        } 

        try 
        {
            //create user
            $user = User::create($data); 
            return $this->responseHandler->dataResponse(true, null, $user, 201);           
        } catch (Exception $e) 
        {
            return $this->responseHandler->dataResponse(false, $e->getMessage(), null, 400);
        }
    }

    public function login(Request $request)
    {
        $validation = $this->userValidation->loginValidation($request);

        if($validation != null) {
            return $this->responseHandler->dataResponse(false, $validation->messages(), null, 400 );
        }

        //get credentials from request
        $credentials = $request->only('email', 'password');

        //if auth failed
        if(!$token = auth()->guard('api')->attempt($credentials)) {
            return $this->responseHandler->dataResponse(false, "Credential not match", null, 401);
        }

        $user = auth()->guard('api')->user();
        return $this->responseHandler->dataResponse(true, null, ["user_id" => $user->user_id, "token" => $token], 200);
    }
}
