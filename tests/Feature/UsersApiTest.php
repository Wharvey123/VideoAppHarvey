<?php

namespace Tests\Feature;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        PermissionHelper::create_user_management_permissions();
        $this->user = UserHelper::create_superadmin_user();
        $this->user->givePermissionTo('manage users');

        // Obtenir token
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => '123456789',
            'device_name' => 'testing'
        ]);

        $this->token = $response->json('token');
    }

    #[Test] public function pot_obtenir_llistat_usuaris()
    {
        User::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'name', 'email', 'created_at']
                ]
            ]);
    }

    #[Test] public function pot_fer_cerques_d_usuaris()
    {
        User::factory()->create(['name' => 'Usuari de prova']);
        User::factory()->create(['name' => 'Altre usuari']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/users?search=prova');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Usuari de prova');
    }

    #[Test] public function pot_obtenir_usuari_especific()
    {
        $user = User::factory()->create();

        // Crear alguns vídeos per l'usuari
        $user->videos()->createMany(
            Video::factory()->count(2)->make()->toArray()
        );

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'videos' => [
                        '*' => ['id', 'title']
                    ]
                ]
            ]);
    }

    #[Test] public function no_autoritzat_sense_token()
    {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
    }
}
