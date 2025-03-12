<?php

namespace Tests\Unit;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\Video;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class VideosTest extends TestCase
{
    use RefreshDatabase;
    #[Test] public function loginAsVideoManager()
    {
        PermissionHelper::create_permissions();
        // Arrange: Crear un usuari amb permís de 'manage videos'
        $user = UserHelper::create_video_manager_user();
        $user->givePermissionTo('manage videos');
        $user->givePermissionTo('videos.create');
        $user->givePermissionTo('videos.edit');
        $user->givePermissionTo('videos.delete');

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }
    public function test_can_get_formatted_published_at_date()
    {
        // Configurar idioma a espanyol
        Carbon::setLocale('es');
        $this->loginAsVideoManager();
        // Crear un vídeo amb una data de publicació
        $video = Video::create([
            'title' => 'Vídeo',
            'description' => 'Description',
            'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
            'published_at' => Carbon::parse('2025-01-17 14:00:00'),
            'series_id' => null,
            'previous' => null,
            'next' => null,
            'user_id' => 1
        ]);

        // Comprovar que el format és correcte
        $this->assertEquals('17 de enero de 2025', $video->formatted_published_at);
    }

    public function test_can_get_formatted_published_at_date_when_not_published()
    {
        // Configurar idioma a espanyol
        Carbon::setLocale('es');
        $this->loginAsVideoManager();
        // Crear un vídeo amb una data de publicació
        $video = Video::create([
            'title' => 'Vídeo',
            'description' => 'Description',
            'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
            'published_at' => null,
            'series_id' => null,
            'previous' => null,
            'next' => null,
            'user_id' => 1
        ]);

        // Comprovar que la propietat retorna null
        $this->assertNull($video->formatted_published_at);
    }
}
