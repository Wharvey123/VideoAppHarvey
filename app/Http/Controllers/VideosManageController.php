<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\Feature\VideosControllerTest;

class VideosManageController extends Controller
{
    // Retorna el nom de la classe de test associada
    public function testedBy(): string
    {
        return VideosControllerTest::class;
    }

    // Mostra la llista de vídeos (CRUD)
    public function index(): View|Factory|Application
    {
        // Comprovar permisos
        if (!auth()->user()->can('manage videos')) {
            abort(403, 'Unauthorized');
        }
        // Obtenir tots els vídeos
        $videos = Video::all();
        // Retornar la vista amb els vídeos
        return view('videos.manage.index', compact('videos'));
    }

    // Mostra el formulari de creació de vídeo
    public function create(): View|Factory|Application
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.create')) {
            abort(403, 'Unauthorized');
        }
        // Retornar la vista del formulari de creació
        return view('videos.manage.create');
    }

    // Desa un vídeo nou
    public function store(Request $request): RedirectResponse
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.create')) {
            abort(403, 'Unauthorized');
        }

        // Validar les dades del formulari
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'url'          => 'required|url',
            'published_at' => 'nullable|date',
            'previous'     => 'nullable|integer',
            'next'         => 'nullable|integer',
            'series_id'    => 'nullable|integer|exists:series,id',
        ]);

        // Assign current user's ID
        $data['user_id'] = auth()->id(); // <-- Add this line

        // Crear el nou vídeo amb les dades validades
        Video::create($data);

        // Redirigir...
        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo creat correctament.');
    }

    // Mostra el formulari d'edició de vídeo
    public function edit($id): View|Factory|Application
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.edit')) {
            abort(403, 'Unauthorized');
        }
        // Obtenir el vídeo per id
        $video = Video::findOrFail($id);
        // Retornar la vista del formulari d'edició amb el vídeo
        return view('videos.manage.edit', compact('video'));
    }

    // Actualitza el vídeo
    public function update(Request $request, $id): RedirectResponse
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.edit')) {
            abort(403, 'Unauthorized');
        }

        // Validar les dades del formulari
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'url'         => 'required|url',
            'published_at'=> 'nullable|date',
            'previous' => 'nullable|integer',
            'next' => 'nullable|integer',
            'series_id'   => 'nullable|integer|exists:series,id',
        ]);

        // Obtenir el vídeo per id
        $video = Video::findOrFail($id);

        // Actualitzar el vídeo amb les dades validades
        $video->update($data);

        // Redirigir a la vista de llista de vídeos amb un missatge d'èxit
        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo actualitzat correctament.');
    }

    // Mostra la confirmació d'eliminació
    public function delete($id): View|Factory|Application
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.delete')) {
            abort(403, 'Unauthorized');
        }
        // Obtenir el vídeo per id
        $video = Video::findOrFail($id);
        // Retornar la vista de confirmació d'eliminació amb el vídeo
        return view('videos.manage.delete', compact('video'));
    }

    // Elimina el vídeo
    public function destroy($id): RedirectResponse
    {
        // Comprovar permisos
        if (!auth()->user()->can('videos.delete')) {
            abort(403, 'Unauthorized');
        }
        // Obtenir el vídeo per id
        $video = Video::findOrFail($id);

        // Eliminar el vídeo
        $video->delete();

        // Redirigir a la vista de llista de vídeos amb un missatge d'èxit
        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo eliminat correctament.');
    }
}
