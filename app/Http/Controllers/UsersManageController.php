<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsersManageController extends Controller
{
    // Funció per als testos (seguint el model de VideosManageController)
    public function testedBy(): string
    {
        return 'UsersManageController testedBy';
    }

    // Mostra la llista d’usuaris per al CRUD (vista index)
    public function index(): View|Factory|Application
    {
        // Comprovar permisos (exemple: només superadmins o usuaris amb permís 'manage users')
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $users = User::all();
        return view('users.manage.index', compact('users'));
    }

    // Mostra el formulari per crear un nou usuari
    public function create(): View|Factory|Application
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        return view('users.manage.create');
    }

    // Emmagatzema un nou usuari
    public function store(Request $request): RedirectResponse
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        // Validació (exemple)
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        // Creació de l'usuari
        User::create($validated);
        return redirect()->route('users.manage.index')->with('success', 'Usuari creat correctament.');
    }

    // Mostra el formulari d'edició per un usuari concret
    public function edit($id): View|Factory|Application
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        return view('users.manage.edit', compact('user'));
    }

    // Processa l'edició de l'usuari
    public function update(Request $request, $id): RedirectResponse
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
        ]);
        $user->update($validated);
        return redirect()->route('users.manage.index')->with('success', 'Usuari actualitzat correctament.');
    }

    // Mostra la confirmació d’eliminació
    public function delete($id): View|Factory|Application
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        return view('users.manage.delete', compact('user'));
    }

    // Elimina l'usuari
    public function destroy($id): RedirectResponse
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.manage.index')->with('success', 'Usuari eliminat correctament.');
    }
}
