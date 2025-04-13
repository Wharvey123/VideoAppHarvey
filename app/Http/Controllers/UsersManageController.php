<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersManageController extends Controller
{
    // Funció per als tests (per exemple, pot retornar informació addicional)
    public function testedBy(): string
    {
        return 'UsersManageController testedBy';
    }

    // Mostra la llista d’usuaris per al CRUD (vista index)
    public function index(): View|Factory|Application
    {
        // Comprovar permisos (només superadmins o usuaris amb permís 'manage users')
        if (! Gate::allows('manage-users')) {
            abort(403);
        }
        $users = User::with(['currentTeam', 'roles'])->get();
        return view('users.manage.index', compact('users'));
    }

    // Mostra el formulari per crear un nou usuari
    public function create(): View|Factory|Application
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }

        $roles = Role::all(); // Obtenir tots els rols disponibles

        return view('users.manage.create', compact('roles'));
    }

    // Emmagatzema un nou usuari
    public function store(Request $request): RedirectResponse
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }

        // Validació del request, s'espera un array per 'roles'
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles'    => 'required|array'
        ]);

        // Creació de l'usuari amb la contrasenya encriptada
        $user = User::create([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Crear o obtenir l'equip personal de l'usuari directament,
        // ja que el mètode add_personal_team està definit al model User.
        $team = $user->add_personal_team();
        $user->current_team_id = $team->id;
        $user->save();

        // Assignar el(s) rol(s) a l'usuari
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('users.manage.index')->with('success', 'Usuari creat correctament.');
    }

    // Mostra el formulari d'edició per un usuari concret
    public function edit($id): View|Factory|Application
    {
        if (! Gate::allows('manage-users')) {
            abort(403);
        }

        $user = User::with('currentTeam')->findOrFail($id);
        $teams = Team::all(); // Obtenir tots els equips
        $roles = Role::all(); // Obtenir tots els rols

        return view('users.manage.edit', compact('user', 'teams', 'roles'));
    }

    // Processa l'edició de l'usuari
    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Validació del request, requerim el camp 'roles' com a array
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => "required|email|unique:users,email,{$id}",
            'current_team_id'  => 'nullable|exists:teams,id',
            'roles'            => 'required|array'
        ]);

        // Si no s'ha seleccionat un equip, crear o obtenir l'equip personal
        if (empty($validated['current_team_id'])) {
            $team = $user->add_personal_team();
            $validated['current_team_id'] = $team->id;
        }

        // Actualitzar l'usuari (sense la contrasenya)
        $user->update($validated);

        // Actualitzar la contrasenya només si s'ha introduït una nova
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Assignar el(s) rol(s) a l'usuari
        $user->syncRoles($request->input('roles', []));

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
