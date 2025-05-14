<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesManageController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->can('manage-series'), 403);
        $series = Serie::all();
        return view('series.manage.index', compact('series'));
    }

    public function create(Request $request): View
    {
        // Eliminar: abort_unless(auth()->user()->can('create-series'), 403);
        $redirect = $request->query('redirect', url()->previous());
        session(['series_create_redirect' => $redirect]);
        return view('series.manage.create', compact('redirect'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Eliminar: abort_unless(auth()->user()->can('create-series'), 403);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|url',
        ]);
        $data['published_at']   = now();
        $data['user_name']      = auth()->user()->name;
        $data['user_photo_url'] = auth()->user()->profile_photo_url;
        Serie::create($data);
        $redirectUrl = session('series_create_redirect', route('series.index'));
        return redirect($redirectUrl)->with('success', 'Sèrie creada correctament.');
    }

    public function edit(Request $request, $id): View
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series')
            || $serie->user_name === auth()->user()->name,
            403
        );
        $redirect = $request->query('redirect', url()->previous());
        return view('series.manage.edit', compact('serie', 'redirect'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series')
            || $serie->user_name === auth()->user()->name,
            403
        );

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|url',
        ]);

        $data['published_at'] = now();
        $serie->update($data);

        $redirect = $request->query('redirect')
            ?? (auth()->user()->can('manage-series')
                ? route('series.manage.index')
                : route('series.index'));

        return redirect($redirect)->with('success', 'Sèrie actualitzada correctament.');
    }

    public function delete(Request $request, $id): View
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series')
            || $serie->user_name === auth()->user()->name,
            403
        );

        $ref = $request->query('redirect', url()->previous());
        $default = str_contains($ref, route('series.show', $id))
            ? route('series.index')
            : route('series.manage.index');

        return view('series.manage.delete', [
            'serie'    => $serie,
            'redirect' => $default,
        ]);
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series')
            || $serie->user_name === auth()->user()->name,
            403
        );

        $ref = $request->query('redirect');
        $isPublic = $ref === route('series.index') || str_contains($ref, '/series');

        $serie->videos()->update(['series_id' => null]);
        $serie->delete();

        $redirect = $isPublic
            ? route('series.index')
            : route('series.manage.index');

        return redirect($redirect)->with('success', 'Sèrie eliminada correctament.');
    }
}
