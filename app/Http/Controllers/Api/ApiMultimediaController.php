<?php

namespace App\Http\Controllers\Api;

use App\Models\Multimedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiMultimediaController extends Controller
{
    /** Mostra tots els arxius multimedia */
    public function index(): JsonResponse
    {
        $media = Multimedia::with('user:id,name,profile_photo_url')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'path' => Storage::url($item->path),
                    'type' => $item->type,
                    'user_id' => $item->user_id,
                    'created_at' => $item->created_at->toDateTimeString(),
                    'user' => $item->user
                ];
            });
        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }

    /** Emmagatzema un nou arxiu multimedia */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:mp4,jpeg,png,jpg|max:20480',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $path = $file->store('multimedia', 'public');

        $media = Multimedia::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'path' => $path,
            'type' => str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image',
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'data' => $media->load('user:id,name'),
            'url' => Storage::url($path)
        ], 201);
    }

    /** Mostra un arxiu específic */
    public function show($id): JsonResponse
    {
        try {
            $media = Multimedia::with('user:id,name,profile_photo_url')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $media->id,
                    'title' => $media->title,
                    'description' => $media->description,
                    'path' => Storage::url($media->path),
                    'type' => $media->type,
                    'user_id' => $media->user_id,
                    'created_at' => $media->created_at->toDateTimeString(),
                    'user' => $media->user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Media not found'
            ], 404);
        }
    }

    /** Actualitza un arxiu existent */
    public function update(Request $request, Multimedia $multimedia): JsonResponse
    {
        // Verificar que l'usuari és el propietari
        if ($multimedia->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No autoritzat'
            ], 403);
        }

        // Validar les dades
        $validated = $request->validate([
            'file' => 'sometimes|file|mimes:mp4,jpeg,png,jpg|max:20480',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        // Gestionar l'actualització del fitxer si s'ha proporcionat
        if ($request->hasFile('file')) {
            // Eliminar el fitxer antic
            Storage::disk('public')->delete($multimedia->path);

            // Pujar el nou fitxer
            $file = $request->file('file');
            $path = $file->store('multimedia', 'public');

            // Actualitzar el path i el tipus
            $multimedia->path = $path;
            $multimedia->type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        }

        // Actualitzar les altres dades
        $multimedia->title = $validated['title'] ?? $multimedia->title;
        $multimedia->description = $validated['description'] ?? $multimedia->description;
        $multimedia->save();

        return response()->json([
            'success' => true,
            'message' => 'Multimedia actualitzat',
            'data' => $multimedia->fresh(['user:id,name']),
            'url' => $multimedia->fresh()->full_url // Assegura't que tens l'atribut full_url al model
        ]);
    }

    /** Elimina un arxiu */
    public function destroy(Multimedia $multimedia): JsonResponse
    {
        // Verifica que l'usuari està autenticat
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticat'
            ], 401);
        }

        // Verifica que l'usuari és el propietari
        if ($multimedia->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tens permisos per eliminar aquest contingut'
            ], 403);
        }

        try {
            // Elimina el fitxer físic
            Storage::disk('public')->delete($multimedia->path);

            // Elimina el registre de la base de dades
            $multimedia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contingut eliminat correctament'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en eliminar el contingut',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userMedia(): JsonResponse
    {
        $media = Multimedia::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'path' => Storage::url($item->path),
                    'type' => $item->type,
                    'created_at' => $item->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $media
        ]);
    }
}
