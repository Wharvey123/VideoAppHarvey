<?php

namespace Tests\Feature\Default;

use App\Helpers\UserHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function usuari_pot_iniciar_sessio()
    {
        $user = UserHelper::create_regular_user();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '123456789',
            'device_name' => 'testing'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    #[Test] public function credencials_invalides_retornen_error()
    {
        $user = UserHelper::create_regular_user();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
            'device_name' => 'testing'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test] public function usuari_pot_registrarse()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'device_name' => 'testing'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }

    #[Test] public function usuari_pot_tancar_sessio()
    {
        $user = UserHelper::create_regular_user();
        $token = $user->createToken('testing')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sessió tancada correctament']);

        $this->assertCount(0, $user->tokens);
    }
}
