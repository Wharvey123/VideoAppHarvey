<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Mostra un vídeo específic.
     * @param int $id
     * @return View|Factory|Application
     */
    public function show(int $id): View|Factory|Application
    {
        // Obtenim el vídeo per id
        $video = Video::findOrFail($id);

        // Comprovar si el vídeo està publicat
        if (!$video->published_at) {
            abort(404, 'Aquest vídeo no està disponible.');
        }

        // Retornem la vista amb el vídeo
        return view('videos.show', compact('video'));
    }

    /**
     * Funció de prova per verificar el bon funcionament del controlador.
     * @return string
     */
    public function testedBy(): string
    {
        return "El controlador de vídeos funciona correctament.";
    }
}
