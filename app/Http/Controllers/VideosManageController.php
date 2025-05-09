<?php

namespace App\Http\Controllers;

use App\Events\VideoCreated;
use App\Models\Video;
use App\Models\Serie;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VideosManageController extends Controller
{
    // Llista de vídeos (només per a gestors)
    public function index(): View|Factory|Application
    {
        abort_unless(auth()->user()->can('manage-videos'), 403);
        $videos = Video::all();
        return view('videos.manage.index', compact('videos'));
    }

    // Formulari de creació (per a qui tingui el permís)
    public function create(Request $request): View|Factory|Application
    {
        abort_unless(auth()->user()->can('videos.create'), 403);

        $series   = Serie::all();
        $seriesId = $request->query('series_id');
        return view('videos.manage.create', compact('series', 'seriesId'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->can('videos.create'), 403);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'url'         => 'required|url',
            'series_id'   => 'nullable|integer|exists:series,id',
        ]);

        $data['published_at'] = now();
        $data['user_id']      = auth()->id();

        $video = Video::create($data);

        // Llançar l'esdeveniment
        event(new VideoCreated($video));

        // Redirigir a la URL anterior o a la pàgina del vídeo
        $redirect = $request->input('redirect', route('video.show', $video->id));

        return redirect($redirect)
            ->with('success', 'Vídeo creat correctament.');
    }


    // Formulari d'edició
    public function edit($id): View|Factory|Application
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );

        $series = Serie::all();
        return view('videos.manage.edit', compact('video', 'series'));
    }

    // Actualitza el vídeo
    public function update(Request $request, $id): RedirectResponse
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'url'          => 'required|url',
            'series_id'    => 'nullable|integer|exists:series,id',
        ]);

        $video->update($data);

        // Decide redirect: admins back to manage, regular back to public show
        if (auth()->user()->can('manage-videos')) {
            $redirect = $request->query('redirect', route('videos.manage.index'));
        } else {
            $redirect = $request->query('redirect', route('video.show', $video->id));
        }

        return redirect($redirect)
            ->with('success', 'Vídeo actualitzat correctament.');
    }

    // Confirmació d'eliminació
    public function delete($id): View|Factory|Application
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );
        return view('videos.manage.delete', compact('video'));
    }

    // Esborra el vídeo
    public function destroy(Request $request, $id): RedirectResponse
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series') || $serie->user_name === auth()->user()->name,
            403
        );

        // Detach videos and delete the series
        $serie->videos()->update(['series_id' => null]);
        $serie->delete();

        // Determine redirect based on user role and query parameter
        $redirect = $request->query('redirect');
        if (!$redirect) {
            $redirect = auth()->user()->can('manage-series')
                ? route('series.manage.index')
                : route('series.index');
        }

        return redirect($redirect)
            ->with('success', 'Sèrie eliminada correctament.');
    }
}
