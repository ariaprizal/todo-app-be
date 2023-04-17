<?php

namespace Tests\Feature;

use App\Models\CardTodo;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;


    ///////////////////////////////////////////////////// Positive test ////////////////////////////////////////////////////

    /**
     * Method test_create_todo_success
     *
     * @return mixed
     */
    public function test_create_todo_success()
    {
        $cardTodo = CardTodo::factory()->create();
        $request = [
            "title" => "makan",
            "description" => "this description",
            "card_todo_id" => $cardTodo->card_todo_id,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->postJson("/api/todo", $request, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(201)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_update_todo_success
     *
     * @return mixed
     */
    public function test_update_todo_success()
    {
        $cardTodo = CardTodo::factory()->create();
        $todo = Todo::factory()->create();
        $request = [
            "title" => "makan",
            "description" => "this description",
            "card_todo_id" => $cardTodo->card_todo_id,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->patchJson("/api/todo/".$todo->id, $request, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }

    
    /**
     * Method test_get_todo_by_card_todo_id_success
     *
     * @return mixed
     */
    public function test_get_todo_by_card_todo_id_success()
    {
        $cardTodo = CardTodo::factory()->create();
        $request = [
            "card_todo_id" => $cardTodo->card_todo_id,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->postJson("/api/todo/card", $request, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_get_todo_by_id_success
     *
     * @return mixed
     */
    public function test_get_todo_by_id_success()
    {
        $todo = Todo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->getJson("/api/todo/".$todo->id, ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_delete_todo_by_id_success
     *
     * @return mixed
     */
    public function test_delete_todo_by_id_success()
    {
        $todo = Todo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        
        $response = $this->deleteJson("/api/todo/".$todo->id,[], ["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }

    //////////////////////////////////////////////////////// Negative Case /////////////////////////////////////////////////

    
    /**
     * Method test_create_todo_with_invalid_param
     *
     * @return mixed
     */
    public function test_create_todo_with_invalid_param()
    {
        $cardTodo = CardTodo::factory()->create();
        $request = [
            "title" => "",
            "description" => "this description",
            "card_todo_id" => $cardTodo->card_todo_id,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->postJson("/api/todo", $request, ["Authorization" => "Bearer ". $token ]);

        $response->assertStatus(400)
            ->assertJsonPath("error.title", ['The title field is required.']);
    }

    
    /**
     * Method test_update_todo_with_invalid_param
     *
     * @return mixed
     */
    public function test_update_todo_with_wrong_token()
    {
        $todo = Todo::factory()->create();
        $request = [
            "title" => "title update",
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->patchJson("/api/todo/".$todo->id, $request, ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
    
    /**
     * Method test_get_todo_by_card_todo_id_with_wrong_token
     *
     * @return mixed
     */
    public function test_get_todo_by_card_todo_id_with_wrong_token()
    {
        $todo = Todo::factory()->create();
        $request = [
            "card_todo_id" => $todo->card_todo_id,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->postJson("/api/todo", $request, ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }

    
    /**
     * Method test_get_detail_todo_with_wrong_token
     *
     * @return mixed
     */
    public function test_get_detail_todo_with_wrong_token()
    {
        $todo = Todo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->getJson("/api/todo/".$todo->id, ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
        
    /**
     * Method test_delete_todo_by_id_with_wrong_token
     *
     * @return mixed
     */
    public function test_delete_todo_by_id_with_wrong_token()
    {
        $todo = Todo::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->deleteJson("/api/todo/".$todo->id, [], ["Authorization" => "Bearer 123456789"]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
}
