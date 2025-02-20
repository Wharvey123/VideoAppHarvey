<?php

use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\VideosController;
use Illuminate\Support\Facades\Route;

// Ruta per a la pàgina d'inici
Route::get('/', function () {
    return redirect()->route('videos.index');
});

// Grup de rutes que requereixen autenticació
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Ruta per al tauler de control
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutes per a la gestió de vídeos (CRUD) només accessibles per usuaris autenticats
    Route::prefix('videos/manage')->name('videos.manage.')->group(function () {
        Route::get('/', [VideosManageController::class, 'index'])->name('index');
        Route::get('/create', [VideosManageController::class, 'create'])->name('create');
        Route::post('/', [VideosManageController::class, 'store'])->name('store');
        Route::get('/{id}', [VideosManageController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VideosManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VideosManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideosManageController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/delete', [VideosManageController::class, 'delete'])->name('delete');
    });
});

// Ruta per mostrar un vídeo específic, accessible per a tots els usuaris
Route::get('/video/{id}', [VideosController::class, 'show'])->name('video.show');

// Ruta per a la pàgina principal de vídeos, accessible per a tots els usuaris
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');

// Ruta per a testos de vídeos, accessible per a tots els usuaris
Route::get('/test-videos', [VideosController::class, 'testedBy']);
