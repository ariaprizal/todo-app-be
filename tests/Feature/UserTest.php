<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Method test_create_user_with_invalid_param
     * Negative Test
     * @return mixed
     */
    public function test_create_user_with_invalid_param()
    {
        $request = [
            'name' => '',
            "email" => "user@email.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];


        $response = $this->postJson("/api/register", $request, []);

        $response->assertStatus(400)
            ->assertJsonPath("error.name", ["The name field is required."]);
    }
    
    /**
     * Method test_create_user_success
     * Postive case
     * @return mixed
     */
    public function test_create_user_success()
    {
        $request = [
            'name' => 'name',
            "email" => "user@email.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];


        $response = $this->postJson("/api/register", $request, []);

        $response->assertStatus(201)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_login_with_invalid_param
     * Negative case
     * @return mixed
     */
    public function test_login_with_invalid_param()
    {
        $request = [
            "email" => "",
            "password" => "password"
        ];


        $response = $this->postJson("/api/login", $request, []);

        $response->assertStatus(400)
            ->assertJsonPath("error.email", ["The email field is required."]);
    }
    
    /**
     * Method test_login_success
     * Postive case
     * @return mixed
     */
    public function test_login_success()
    {
        $user = User::factory()->create();
        $request = [
            "email" => $user->email,
            "password" => 'password',
        ];

        $response = $this->postJson("/api/login", $request, []);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_get_all_user_success
     * Positive case
     * @return mixed
     */
    public function test_get_all_user_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->getJson("/api/user",["Authorization" => "Bearer " . $token]);

        $response->assertStatus(200)
            ->assertJsonPath("success", true);
    }
    
    /**
     * Method test_get_all_user_with_invalid_token
     * Negative case
     * @return mixed
     */
    public function test_get_all_user_with_invalid_token()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $token = JWTAuth::fromUser($user);
        $response = $this->getJson("/api/user",["Authorization" => "Bearer 123456789" ]);

        $response->assertStatus(400)
            ->assertJsonPath("message", "Wrong number of segments");
    }
}
