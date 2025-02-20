<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Tests\Feature\VideosControllerTest;

class VideosController extends Controller
{
    /**Mostra tots els vídeos publicats a la pàgina principal.
     * @return View|Factory|Application  */
    public function index(): View|Factory|Application
    {
        // Recuperem tots els vídeos publicats (on 'published_at' no és null)
        $videos = Video::whereNotNull('published_at')->get();

        // Retornem la vista 'videos.index' amb la variable $videos
        return view('videos.index', compact('videos'));
    }

    /** Mostra un vídeo específic.
     * @param int $id
     * @return View|Factory|Application */
    public function show(int $id): View|Factory|Application
    {
        // Obtenim el vídeo per id
        $video = Video::findOrFail($id);

        // Comprovar si el vídeo és publicat
        if (!$video->published_at) {
            abort(404, 'Aquest vídeo no està disponible.');
        }

        // Retornem la vista amb el vídeo
        return view('videos.show', compact('video'));
    }

    /** Retorna el nom complet de la classe de test associada.
     * @return string */
    public function testedBy(): string
    { return VideosControllerTest::class; }
}
