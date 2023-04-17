<?php

use App\Http\Controllers\CardTodoController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\authMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([authMiddleware::class])->group(function () {
    Route::get("/user", [UserController::class, "getUser"]);
    
    
    Route::post("/todo", [TodoController::class, "create"]);
    Route::post("/todo/card", [TodoController::class, "getByCardTodoId"]);
    Route::get("/todo/{id}", [TodoController::class, "todoDetail"]);
    Route::patch("/todo/{id}", [TodoController::class, "updateTodo"]);
    Route::delete("/todo/{id}", [TodoController::class, "deleteTodo"]);
    
    Route::post("/card-todo", [CardTodoController::class, "create"]);
    Route::get("/card-todo", [CardTodoController::class, "getAllCardTodo"]);
    Route::delete("/card-todo/{id}", [CardTodoController::class, "deleteCardTodo"]);


    Route::post("/register", [UserController::class, "register"])->withoutMiddleware([authMiddleware::class]);
    Route::post("/login", [UserController::class, "login"])->withoutMiddleware([authMiddleware::class]);
});
