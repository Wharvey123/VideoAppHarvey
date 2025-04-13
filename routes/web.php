<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesManageController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use Illuminate\Support\Facades\Route;

// Ruta per a la pàgina d'inici (redirigeix a vídeos)
Route::get('/', function () {
    return redirect()->route('videos.index');
});

// --------------------------------------------------------
// RUTES PÚBLIQUES (accesibles sense autenticació)
// --------------------------------------------------------

// Vídeos
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
Route::get('/video/{id}', [VideosController::class, 'show'])->name('video.show');

// Sèries
Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{id}', [SeriesController::class, 'show'])->name('series.show');

// Usuaris
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

// --------------------------------------------------------
// RUTES PROTEGIDES (requereixen autenticació i permisos)
// --------------------------------------------------------
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Tauler de control
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --------------------------------------------------------
    // RUTES PER CREAR I EMMAGATZEMAR VÍDEOS
    // Només requereixen el permís "videos.create" (per usuaris regulars)
    // --------------------------------------------------------
    Route::middleware('can:videos.create')->group(function () {
        Route::get('/videos/manage/create', [VideosManageController::class, 'create'])
            ->name('videos.manage.create');
        Route::post('/videos/manage', [VideosManageController::class, 'store'])
            ->name('videos.manage.store');
    });

    // --------------------------------------------------------
    // VÍDEOS MANAGEMENT - Editar, Actualitzar i Esborrar (requereix "manage-videos")
    // --------------------------------------------------------
    Route::middleware('can:manage-videos')->prefix('videos/manage')->name('videos.manage.')->group(function () {
        Route::get('/', [VideosManageController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VideosManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VideosManageController::class, 'update'])->name('update');
        Route::get('/{id}/delete', [VideosManageController::class, 'delete'])->name('delete');
        Route::delete('/{id}', [VideosManageController::class, 'destroy'])->name('destroy');
    });

    // --------------------------------------------------------
    // USUARIS MANAGEMENT (CRUD)
    // --------------------------------------------------------
    Route::prefix('usersmanage')
        ->name('users.manage.')
        ->middleware('can:manage-users')
        ->group(function () {
            Route::get('/', [UsersManageController::class, 'index'])->name('index');
            Route::get('/create', [UsersManageController::class, 'create'])
                ->middleware('can:create-users')
                ->name('create');
            Route::post('/', [UsersManageController::class, 'store'])
                ->middleware('can:create-users')
                ->name('store');
            Route::get('/{id}/edit', [UsersManageController::class, 'edit'])
                ->middleware('can:edit-users')
                ->name('edit');
            Route::put('/{id}', [UsersManageController::class, 'update'])
                ->middleware('can:edit-users')
                ->name('update');
            Route::get('/{id}/delete', [UsersManageController::class, 'delete'])
                ->middleware('can:delete-users')
                ->name('delete');
            Route::delete('/{id}', [UsersManageController::class, 'destroy'])
                ->middleware('can:delete-users')
                ->name('destroy');
        });

    // --------------------------------------------------------
    // SÈRIES MANAGEMENT (CRUD)
    // --------------------------------------------------------
    Route::prefix('seriesmanage')
        ->name('series.manage.')
        ->middleware('can:manage-series')
        ->group(function () {
            Route::get('/', [SeriesManageController::class, 'index'])->name('index');
            Route::get('/create', [SeriesManageController::class, 'create'])
                ->middleware('can:create-series')
                ->name('create');
            Route::post('/', [SeriesManageController::class, 'store'])
                ->middleware('can:create-series')
                ->name('store');
            Route::get('/{id}/edit', [SeriesManageController::class, 'edit'])
                ->middleware('can:edit-series')
                ->name('edit');
            Route::put('/{id}', [SeriesManageController::class, 'update'])
                ->middleware('can:edit-series')
                ->name('update');
            Route::get('/{id}/delete', [SeriesManageController::class, 'delete'])
                ->middleware('can:delete-series')
                ->name('delete');
            Route::delete('/{id}', [SeriesManageController::class, 'destroy'])
                ->middleware('can:delete-series')
                ->name('destroy');
        });
});
