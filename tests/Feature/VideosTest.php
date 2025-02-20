<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function user_without_permissions_can_see_default_videos_page()
    {
        // Arrange: Crear un usuari sense permisos específics
        $user = User::factory()->create();

        // Act: Autenticar l'usuari i fer una sol·licitud GET a la pàgina de vídeos
        $response = $this->actingAs($user)->get('/videos');

        // Assert: Verificar que la resposta és correcta (200 OK)
        $response->assertStatus(200);
    }

    #[Test] public function user_with_permissions_can_see_default_videos_page()
    {
        // Arrange: Crear el permís i un usuari amb aquest permís
        Permission::create(['name' => 'view videos']);
        $user = User::factory()->create();
        $user->givePermissionTo('view videos');

        // Act: Autenticar l'usuari i fer una sol·licitud GET a la pàgina de vídeos
        $response = $this->actingAs($user)->get('/videos');

        // Assert: Verificar que la resposta és correcta (200 OK)
        $response->assertStatus(200);
    }

    #[Test] public function not_logged_users_can_see_default_videos_page()
    {
        // Act: Fer una sol·licitud GET a la pàgina de vídeos sense autenticar
        $response = $this->get('/videos');

        // Assert: Verificar que la resposta és correcta (200 OK)
        $response->assertStatus(200);
    }
}
