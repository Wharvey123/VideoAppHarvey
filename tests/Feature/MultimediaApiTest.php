<?php

namespace Tests\Feature;

use App\Models\Multimedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultimediaApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public'); // Configurar fake storage per a cada test

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'testing'
        ]);

        $this->token = $response->json('token');
    }

    #[Test] public function pot_obtenir_llistat_multimedia()
    {
        // Utilitzem el factory correctament
        Multimedia::factory()
            ->count(3)
            ->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/multimedia');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description', 'path', 'type']
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    #[Test] public function pot_pujar_nou_arxiu_multimedia()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/multimedia', [
            'file' => $file,
            'title' => 'Test Image',
            'description' => 'Descripció de prova'
        ]);

        $response->assertStatus(201);

        $multimedia = Multimedia::first();
        $this->assertNotNull($multimedia);

        // Verificar que el path és correcte
        $this->assertStringStartsWith('multimedia/', $multimedia->path);

        // Verificar que l'arxiu existeix al storage
        Storage::disk('public')->assertExists($multimedia->path);
    }

    #[Test] public function pot_eliminar_arxiu_multimedia()
    {
        // Crear un fitxer fake al storage
        $filePath = 'multimedia/test_delete.jpg';
        Storage::disk('public')->put($filePath, 'Test content');

        // Crear registre a la base de dades
        $multimedia = Multimedia::factory()->create([
            'user_id' => $this->user->id,
            'path' => $filePath
        ]);

        // Verificar que l'arxiu existeix abans d'eliminar
        Storage::disk('public')->assertExists($filePath);

        // Fer petició DELETE
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/multimedia/{$multimedia->id}");

        // Verificar resposta
        $response->assertStatus(200)
            ->assertJson(['message' => 'Multimedia eliminat']);

        // Verificar que s'ha eliminat de la base de dades
        $this->assertDatabaseMissing('multimedia', ['id' => $multimedia->id]);

        // Verificar que s'ha eliminat el fitxer físic
        Storage::disk('public')->assertMissing($filePath);
    }
}
