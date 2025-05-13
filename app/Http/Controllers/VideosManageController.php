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
    public function index(): View|Factory|Application
    {
        abort_unless(auth()->user()->can('manage-videos'), 403);
        $videos = Video::all();
        return view('videos.manage.index', compact('videos'));
    }

    public function create(Request $request): View|Factory|Application
    {
        abort_unless(auth()->user()->can('videos.create'), 403);
        $series   = Serie::all();
        $seriesId = $request->query('series_id');
        $redirect = $request->query('redirect', url()->previous());

        return view('videos.manage.create', compact('series', 'seriesId', 'redirect'));
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
        event(new VideoCreated($video));

        $redirect = $request->input('redirect', route('video.show', $video->id));
        return redirect($redirect)->with('success', 'Vídeo creat correctament.');
    }

    public function edit(Request $request, $id): View|Factory|Application
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );
        $series   = Serie::all();
        $redirect = $request->query('redirect', url()->previous());

        return view('videos.manage.edit', compact('video', 'series', 'redirect'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'url'         => 'required|url',
            'series_id'   => 'nullable|integer|exists:series,id',
        ]);

        $video->update($data);
        $redirect = $request->query('redirect');
        if (! $redirect) {
            $redirect = auth()->user()->can('manage-videos')
                ? route('videos.manage.index')
                : route('video.show', $video->id);
        }

        return redirect($redirect)->with('success', 'Vídeo actualitzat correctament.');
    }

    public function delete(Request $request, $id): View|Factory|Application
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );

        // Si venim des de public.show, forcem redirect a videos.index
        $default = url()->previous() === route('video.show', $id)
            ? route('videos.index')
            : route('videos.manage.index');
        $redirect = $request->query('redirect', $default);

        return view('videos.manage.delete', compact('video', 'redirect'));
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $video = Video::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-videos') || $video->user_id === auth()->id(),
            403
        );

        $video->delete();
        $redirect = $request->query('redirect',
            auth()->user()->can('manage-videos')
                ? route('videos.manage.index')
                : route('videos.index')
        );

        return redirect($redirect)->with('success', 'Vídeo eliminat correctament.');
    }
}
