<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class SeriesController extends Controller
{
    public function index(Request $request): View
    {
        // Si hi ha paràmetre de cerca, filtra per títol
        $query = Serie::query();
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }
        $series = $query->get();

        return view('series.index', compact('series'));
    }

    public function show($id): View
    {
        $serie = Serie::with('videos')->findOrFail($id);
        return view('series.show', compact('serie'));
    }
}
