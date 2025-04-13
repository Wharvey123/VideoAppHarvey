<?php

namespace Tests\Feature\Videos;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideosControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prova que la pàgina d'inici retorna una resposta exitosa.
     */
    public function test_pagina_inici_resposta_exitosa()
    {
        // Realitza una petició GET a la ruta '/'
        $response = $this->get('/');

        // Segueix la redirecció i verifica que la resposta final té un codi d'estat 200
        $response->assertRedirect(route('videos.index'));
        $response = $this->get(route('videos.index'));
        $response->assertStatus(200);
    }

}
