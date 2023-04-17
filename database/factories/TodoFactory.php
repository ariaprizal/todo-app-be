<?php

namespace Database\Factories;

use App\Models\CardTodo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cardTodo = CardTodo::factory()->create();
        return [
            "title" => "testTodo",
            "description" => "this description",
            "card_todo_id" => $cardTodo->card_todo_id,
        ];
    }
}
