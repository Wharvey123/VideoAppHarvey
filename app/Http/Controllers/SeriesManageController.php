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

    public function create(): View
    {
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
        // Assegura't que el camp utilitzat coincideixi amb el nom definit al model d'usuari.
        $data['user_name'] = auth()->check() ? auth()->user()->name : 'Usuari Desconegut';
        $data['user_photo_url'] = auth()->check() ? auth()->user()->profile_photo_url : null;

        // Crear la sèrie
        \App\Models\Serie::create($data);

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie creada correctament.');
    }

    /**
     * Mostra el formulari d'edició de la sèrie.
     */
    public function edit($id): View
    {
        $serie = Serie::findOrFail($id);
        return view('series.manage.edit', compact('serie'));
    }

    /**
     * Actualitza la sèrie.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'   => 'required|url'
        ]);

        // En editar també actualitzem la data de publicació amb la data actual
        $data['published_at'] = now();

        // Mantenim la informació original de l'usuari sense permetre modificacions
        $serie = Serie::findOrFail($id);
        $serie->update($data);

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie actualitzada correctament.');
    }

    /**
     * Mostra la vista de confirmació per a l'eliminació.
     */
    public function delete($id): View
    {
        $serie = Serie::findOrFail($id);
        return view('series.manage.delete', compact('serie'));
    }

    /**
     * Elimina la sèrie (i els vídeos associats o desassigna la relació).
     */
    public function destroy($id): RedirectResponse
    {
        $serie = Serie::findOrFail($id);
        // Eliminar la sèrie i desassignar els vídeos (posar series_id a null)
        $serie->videos()->update(['series_id' => null]);
        $serie->delete();

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie i vídeos associats gestionats correctament.');
    }
}
