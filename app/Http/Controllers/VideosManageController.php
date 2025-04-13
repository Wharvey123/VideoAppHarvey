<?php


namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Serie;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tests\Feature\Videos\VideosControllerTest;

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
        if (!auth()->user()->can('manage videos')) {
            abort(403, 'Unauthorized');
        }
        $videos = Video::all();
        return view('videos.manage.index', compact('videos'));
    }

    // Mostra el formulari de creació de vídeo
    public function create(Request $request): View|Factory|Application
    {
        if (!auth()->user()->can('videos.create')) {
            abort(403, 'Unauthorized');
        }
        // Obtenir totes les sèries disponibles per el selector
        $series = Serie::all();
        // Recollir l'id de la sèrie preseleccionada (si s'ha passat per paràmetre)
        $seriesId = $request->query('series_id');
        return view('videos.manage.create', compact('series', 'seriesId'));
    }

    // Desa un vídeo nou
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->can('videos.create')) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'url'          => 'required|url',
            'published_at' => 'nullable|date',
            'previous'     => 'nullable|integer',
            'next'         => 'nullable|integer',
            'series_id'    => 'nullable|integer|exists:series,id',
        ]);

        $data['published_at'] = now();
        $data['user_id'] = auth()->id();

        // Create video and store reference for redirection
        $video = Video::create($data);

        // Redirect to the public show page for the created video
        return redirect()->route('video.show', $video->id)
            ->with('success', 'Vídeo creat correctament.');
    }


    // Mostra el formulari d'edició de vídeo
    public function edit($id): View|Factory|Application
    {
        if (!auth()->user()->can('videos.edit')) {
            abort(403, 'Unauthorized');
        }

        $video = Video::findOrFail($id);
        $series = Serie::all(); // Fetch all series

        return view('videos.manage.edit', compact('video', 'series')); // Pass series to the view
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
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'url'          => 'required|url',
            'published_at' => 'nullable|date',  // Aquest camp també es pot sobreescriure si cal
            'previous'     => 'nullable|integer',
            'next'         => 'nullable|integer',
            'series_id'    => 'nullable|integer|exists:series,id',
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
