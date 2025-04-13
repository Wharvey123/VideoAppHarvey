<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\Feature\Videos\VideosControllerTest;

class VideosController extends Controller
{
    /**
     * Mostra tots els vídeos publicats a la pàgina principal.
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        $videos = Video::whereNotNull('published_at')->get();
        return view('videos.index', compact('videos'));
    }

    /**
     * Mostra un vídeo específic.
     * @param int $id
     * @return View|Factory|Application
     */
    public function show(int $id): View|Factory|Application
    {
        $video = Video::findOrFail($id);

        if (!$video->published_at) {
            abort(404, 'Aquest vídeo no està disponible.');
        }

        return view('videos.show', compact('video'));
    }

    /**
     * Retorna llistat de vídeos per a l'API (nou mètode)
     * @return JsonResponse
     */
    public function apiIndex(): JsonResponse
    {
        $videos = Video::whereNotNull('published_at')
            ->with('user')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }

    /**
     * Retorna un vídeo específic per a l'API (nou mètode)
     * @param int $id
     * @return JsonResponse
     */
    public function apiShow(int $id): JsonResponse
    {
        $video = Video::with('user')->findOrFail($id);

        if (!$video->published_at) {
            return response()->json([
                'success' => false,
                'message' => 'Aquest vídeo no està disponible.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $video
        ]);
    }

    public function apiStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'published_at' => 'nullable|date'
        ]);

        $video = Video::create(array_merge($validated, [
            'user_id' => auth()->id()
        ]));

        return response()->json([
            'success' => true,
            'data' => $video
        ], 201);
    }

    /**
     * Retorna el nom complet de la classe de test associada.
     * @return string
     */
    public function testedBy(): string
    {
        return VideosControllerTest::class;
    }
}
