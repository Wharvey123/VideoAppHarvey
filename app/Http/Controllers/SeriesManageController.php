<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SeriesManageController extends Controller
{
    /**
     * Retorna el nom de la classe de test associada.
     */
    public function testedBy(): string
    {
        return self::class . 'Test';
    }

    public function index(): View
    {
        $series = Serie::all();
        return view('series.manage.index', compact('series'));
    }

    public function create(Request $request): View
    {
        // Emmagatzema la URL anterior a la sessió
        session(['series_create_redirect' => url()->previous()]);

        return view('series.manage.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validar els camps obligatoris
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|url',
        ]);

        // Assignar la data actual de publicació
        $data['published_at'] = now();

        // Assignar la informació de l'usuari: nom i foto de perfil
        $data['user_name'] = auth()->check() ? auth()->user()->name : 'Usuari Desconegut';
        $data['user_photo_url'] = auth()->check() ? auth()->user()->profile_photo_url : null;

        // Crear la sèrie
        Serie::create($data);

        // Redirigir a la URL anterior o a una ruta per defecte
        $redirectUrl = session('series_create_redirect', route('series.index'));
        return redirect($redirectUrl)->with('success', 'Sèrie creada correctament.');
    }

    /**
     * Mostra el formulari d'edició de la sèrie.
     */
    public function edit($id): View
    {
        $serie = Serie::findOrFail($id);

        // Comprova si l'usuari té permís per gestionar sèries o és el propietari
        if (!auth()->user()->can('manage-series') && $serie->user_name !== auth()->user()->name) {
            abort(403);
        }

        return view('series.manage.edit', compact('serie'));
    }


    /**
     * Actualitza la sèrie.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $serie = Serie::findOrFail($id);
        abort_unless(
            auth()->user()->can('manage-series') || $serie->user_name === auth()->user()->name,
            403
        );

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|url',
        ]);

        $data['published_at'] = now();
        $serie->update($data);

        // Redirect back
        if (auth()->user()->can('manage-series')) {
            $redirect = $request->query('redirect', route('series.manage.index'));
        } else {
            $redirect = $request->query('redirect', route('series.index'));
        }

        return redirect($redirect)
            ->with('success', 'Sèrie actualitzada correctament.');
    }

    /**
     * Mostra la vista de confirmació per a l'eliminació.
     */
    public function delete($id): View
    {
        $serie = Serie::findOrFail($id);

        // Comprova si l'usuari té permís per gestionar sèries o és el propietari
        if (!auth()->user()->can('manage-series') && $serie->user_name !== auth()->user()->name) {
            abort(403);
        }

        return view('series.manage.delete', compact('serie'));
    }


    /**
     * Elimina la sèrie (i els vídeos associats o desassigna la relació).
     */
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

        // Determine the redirect URL
        if (auth()->user()->can('manage-series')) {
            $redirect = $request->query('redirect', route('series.manage.index'));
        } else {
            // Use the redirect parameter or default to series.index
            $redirect = $request->query('redirect', route('series.index'));
        }

        return redirect($redirect)
            ->with('success', 'Sèrie eliminada correctament.');
    }
}
