<?php

use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use Illuminate\Support\Facades\Route;

// Ruta per a la pàgina d'inici
Route::get('/', function () {
    return redirect()->route('videos.index');
});

// Rutes públiques per a vídeos
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
Route::get('/video/{id}', [VideosController::class, 'show'])->name('video.show');

// Rutes per a usuaris que requereixen autenticació
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
});

// Grup de rutes que requereixen autenticació
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Ruta per al tauler de control
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutes per a la gestió de vídeos (CRUD)
    Route::prefix('videos/manage')
        ->name('videos.manage.')
        ->middleware('can:manage-videos')
        ->group(function () {
            Route::get('/', [VideosManageController::class, 'index'])->name('index');
            Route::get('/create', [VideosManageController::class, 'create'])->name('create');
            Route::post('/', [VideosManageController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [VideosManageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [VideosManageController::class, 'update'])->name('update');
            Route::delete('/{id}', [VideosManageController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/delete', [VideosManageController::class, 'delete'])->name('delete');
        });

    // Rutes per a la gestió d'usuaris (CRUD)
    Route::prefix('usersmanage')
        ->name('users.manage.')
        ->middleware('can:manage-users')
        ->group(function () {
            Route::get('/', [UsersManageController::class, 'index'])->name('index');
            Route::get('/create', [UsersManageController::class, 'create'])->middleware('can:create-users')->name('create');
            Route::post('/', [UsersManageController::class, 'store'])->middleware('can:create-users')->name('store');
            Route::get('/{id}/edit', [UsersManageController::class, 'edit'])->middleware('can:edit-users')->name('edit');
            Route::put('/{id}', [UsersManageController::class, 'update'])->middleware('can:edit-users')->name('update');
            Route::delete('/{id}', [UsersManageController::class, 'destroy'])->middleware('can:delete-users')->name('destroy');
            Route::get('/{id}/delete', [UsersManageController::class, 'delete'])->middleware('can:delete-users')->name('delete');
        });
});
