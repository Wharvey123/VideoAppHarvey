<?php

namespace Tests\Unit;

use App\Models\Video;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class VideosTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_get_formatted_published_at_date()
    {
        // Configurar idioma a espanyol
        Carbon::setLocale('es');

        // Crear un vídeo amb una data de publicació
        $video = Video::factory()->create([
            'published_at' => Carbon::parse('2025-01-17 14:00:00'),
        ]);

        // Comprovar que el format és correcte
        $this->assertEquals('17 de enero de 2025', $video->formatted_published_at);
    }

    public function test_can_get_formatted_published_at_date_when_not_published()
    {
        // Crear un vídeo sense data de publicació
        $video = Video::factory()->create([
            'published_at' => null,
        ]);

        // Comprovar que la propietat retorna null
        $this->assertNull($video->formatted_published_at);
    }
}
