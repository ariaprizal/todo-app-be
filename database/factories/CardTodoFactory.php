<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        return [
            "user_id" => $user->user_id,
            "card_code" => 'CARD001'
        ];
    }
}
