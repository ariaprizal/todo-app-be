<?php

namespace App\Http\Controllers;

use App\Http\Helper\ResponseHandler;
use App\Http\Helper\TodoValidation;
use App\Models\Todo;
use Exception;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    private $todoValidation;
    private $responseHandler;
    function __construct(TodoValidation $todoValidation, ResponseHandler $responseHandler)
    {
        $this->todoValidation = $todoValidation;
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
        $data['status'] = 'running';
        $data['check'] = 'false';
        $validation = $this->todoValidation->createValidation($request);

        if($validation != null) {
            return $this->responseHandler->dataResponse(false, $validation->messages(), null, 400, );
        } 
        
        try {
            $todo = Todo::create($data);            
            return $this->responseHandler->dataResponse(true, null, $todo, 201);
        } catch (Exception $e) {
            return $this->responseHandler->dataResponse(false, $e->getMessage(), null, 400); 
        }
    }
    
    /**
     * Method getByCardTodoId
     *
     * @param $card_todo_id $card_todo_id [explicite description]
     *
     * @return mixed
     */
    public function getByCardTodoId(Request $request) 
    {
        $data = $request->all();
        $todos = Todo::where('card_todo_id', $data['card_todo_id'])->get();

        return $this->responseHandler->dataResponse(true, null, $todos, 200);
    }
    
    /**
     * Method updateTodo
     *
     * @param Request $request [explicite description]
     * @param $id $id [explicite description]
     *
     * @return mixed
     */
    public function updateTodo(Request $request, $id)
    {
        $data = $request->all();
        
        $todo = tap(Todo::findOrFail($id))->updateOrFail($data);
        return $this->responseHandler->dataResponse(true, null, $todo, 200);
    }
    
    /**
     * Method deleteTodo
     *
     * @param $id $id [explicite description]
     *
     * @return mixed
     */
    public function deleteTodo($id)
    {
        Todo::findOrFail($id)->delete();
        return $this->responseHandler->dataResponse(true, null, 'Succeefully delete data', 200);
    }
    
    /**
     * Method todoDetail
     *
     * @param $id $id [explicite description]
     *
     * @return mixed
     */
    public function todoDetail($id)
    {
        $todo = Todo::find($id);
        if ($todo == null) 
        {
            return $this->responseHandler->dataResponse(false, 'Data Not found' , $todo, 404);
        }
        return $this->responseHandler->dataResponse(true, null, $todo, 200);
    }
}
