<?php

namespace App\Http\Controllers;

use App\Http\Helper\CardTodoValidation;
use App\Http\Helper\ResponseHandler;
use App\Models\CardTodo;
use App\Models\Todo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class CardTodoController extends Controller
{
    private $cardTodoValidation;
    private $responseHandler;
    function __construct(CardTodoValidation $cardTodoValidation, ResponseHandler $responseHandler)
    {
        $this->cardTodoValidation = $cardTodoValidation;
        $this->responseHandler = $responseHandler;
    }
        
    /**
     * Method create
     *
     * @param Request $request [explicite description]
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $code = Carbon::now()->getTimestamp(); 
        $data['card_code'] = "CARD".$code;
        $validation = $this->cardTodoValidation->createValidation($request);

        if($validation != null) {
            return $this->responseHandler->dataResponse(false, $validation->messages(), null, 400, );
        } 
        
        try {
            $cardTodo = CardTodo::create($data);            
            return $this->responseHandler->dataResponse(true, null, $cardTodo, 201);
        } catch (Exception $e) {
            return $this->responseHandler->dataResponse(false, $e->getMessage(), null, 400); 
        }
    }
    
    /**
     * Method getAllCardTodo
     *
     * @return mixed
     */
    public function getAllCardTodo()
    {
        $cardTodo = json_decode(CardTodo::all()->toJson());
        $cardResponse = [];
        foreach ($cardTodo as $card) 
        {
            $todo = json_decode(json_encode($card), true);
            $todos = Todo::where('card_todo_id', $card->card_todo_id)->get();
            $todo['todos'] = $todos;  
            array_push($cardResponse, $todo);          
        }
        return $this->responseHandler->dataResponse(true, null, $cardResponse, 200);
    }

    /**
     * Method deleteTodo
     *
     * @param $id $id [explicite description]
     *
     * @return mixed
     */
    public function deleteCardTodo($id)
    {
        CardTodo::findOrFail($id)->delete();
        return $this->responseHandler->dataResponse(true, null, 'Succeefully delete data', 200);
    }
}
