<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Vista pública amb la llista d'usuaris i cerca
     * @param Request $request
     * @return View|Factory|Application
     */
    public function index(Request $request): View|Factory|Application
    {
        $query = User::query();
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->get();
        return view('users.index', compact('users'));
    }

    /**
     * Mostra el detall d'un usuari i els seus vídeos
     * @param $id
     * @return View|Factory|Application
     */
    public function show($id): View|Factory|Application
    {
        $user = User::findOrFail($id);
        $videos = $user->videos ?? [];
        return view('users.show', compact('user', 'videos'));
    }

    /**
     * Retorna llistat d'usuaris per a l'API (nou mètode)
     * @param Request $request
     * @return JsonResponse
     */
    public function apiIndex(Request $request): JsonResponse
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /** Retorna un usuari específic per a l'API (nou mètode)
     * @param int $id
     * @return JsonResponse
     */
    public function apiShow(int $id): JsonResponse
    {
        $user = User::with('videos')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'videos' => $user->videos
            ]
        ]);
    }
}
