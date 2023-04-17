<?php

namespace Tests\Feature;

use App\Models\CardTodo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class CardTodoTest extends TestCase
{
    use RefreshDatabase;

    ///////////////////////////////////////////////////// Positive test ////////////////////////////////////////////////////

    /**
     * Method test_create_todo_success
     *
     * @return mixed
     */
    public function test_create_card_todo_success()
    {
        $user = User::factory()->create();
        $request = [
            "user_id" => $user->user_id,
        ];

        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->postJson("/api/card-todo", $request, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(201)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_get_todo_by_card_todo_id_success
     *
     * @return mixed
     */
    public function test_get_card_todos_success()
    {

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->getJson("/api/card-todo", ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_delete_todo_by_id_success
     *
     * @return mixed
     */
    public function test_delete_card_todo_by_id_success()
    {
        $cardTodo = CardTodo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->deleteJson("/api/card-todo/".$cardTodo->card_todo_id, [], ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }


    ///////////////////////////////////////////////////// Negative test ////////////////////////////////////////////////////

    /**
     * Method test_create_card_todo_with_invalid_param
     *
     * @return mixed
     */
    public function test_create_card_todo_with_invalid_param()
    {
        $request = [
            "user_id" => 100,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->postJson("/api/card-todo", $request, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(400)
            ->assertJsonPath("error.user_id", ['The selected user id is invalid.']);
    }
    
    /**
     * Method test_get_card_todos_with_ivalid_token
     *
     * @return mixed
     */
    public function test_get_card_todos_with_ivalid_token()
    {

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->getJson("/api/card-todo", ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
    
    /**
     * Method test_delete_card_todo_with_invalid_token
     *
     * @return mixed
     */
    public function test_delete_card_todo_with_invalid_token()
    {
        $cardTodo = CardTodo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->deleteJson("/api/card-todo/".$cardTodo->card_todo_id, [], ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
}
