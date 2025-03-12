<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    // Vista pública amb la llista d'usuaris i cerca
    public function index(Request $request): View|Factory|Application
    {
        // Assegura que l'usuari està autenticat
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }
        $query = User::query();
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }
        $users = $query->get();
        return view('users.index', compact('users'));
    }

    // Mostra el detall d'un usuari i els seus vídeos
    public function show($id): View|Factory|Application
    {
        // Assegura que l'usuari està autenticat
        if (!auth()->check()) {
            abort(403, 'Unauthorized action.');
        }
        $user = User::findOrFail($id);
        // Suposem que la relació amb vídeos és 'videos'
        $videos = $user->videos ?? [];
        return view('users.show', compact('user', 'videos'));
    }
}
