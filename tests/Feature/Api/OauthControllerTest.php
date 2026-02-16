<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// тест не рабочий
class OauthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $apiPath = '/api/v1/oauth';

    public function test_register(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson("{$this->apiPath}/register", $data);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token',
                'name',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson("{$this->apiPath}/login", $data);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token',
                'name',
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $data = [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson("{$this->apiPath}/login", $data);

        $response
            ->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid credentials',
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:client', ['--client' => true]);
    }
}
