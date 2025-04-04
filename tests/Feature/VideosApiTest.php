<?php

namespace Tests\Feature;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VideosApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuari i obtenir token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    #[Test] public function pot_obtenir_llistat_videos()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/videos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'title', 'description', 'url', 'published_at']
                ]
            ]);
    }

    #[Test] public function pot_obtenir_video_especific()
    {
        $video = Video::factory()->create(['published_at' => now()]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/video/{$video->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'title' => $video->title
                ]
            ]);
    }

    #[Test] public function video_no_publicat_retorna_error()
    {
        $video = Video::factory()->create(['published_at' => null]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/video/{$video->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Aquest vídeo no està disponible.'
            ]);
    }

    #[Test] public function pot_crear_video()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/videos', [
            'title' => 'Nou vídeo',
            'description' => 'Descripció del vídeo',
            'url' => 'https://example.com/video.mp4',
            'published_at' => now()->format('Y-m-d H:i:s')
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Nou vídeo'
                ]
            ]);

        $this->assertDatabaseHas('videos', ['title' => 'Nou vídeo']);
    }
}
