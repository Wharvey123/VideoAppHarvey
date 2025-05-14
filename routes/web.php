<?php

use App\Http\Controllers\SeriesController;
use App\Http\Controllers\SeriesManageController;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\VideosManageController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersManageController;
use Illuminate\Support\Facades\Route;

// Ruta per a la pàgina d'inici (redirigeix a vídeos)
Route::get('/', fn() => redirect()->route('videos.index'));

// Mostrar notificacions al panel d'usuari
Route::middleware(['auth'])->get('/notifications', function () {
    return view('notifications.index');
})->name('notifications.index');

// --------------------------------------------------------
// RUTES PÚBLIQUES (accesibles sense autenticació)
// --------------------------------------------------------
Route::get('/videos', [VideosController::class, 'index'])->name('videos.index');
Route::get('/video/{id}', [VideosController::class, 'show'])->name('video.show');

Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{id}', [SeriesController::class, 'show'])->name('series.show');

Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');

// --------------------------------------------------------
// RUTES PROTEGIDES (requereixen autenticació)
// --------------------------------------------------------
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', fn() => redirect()->route('videos.index'))->name('dashboard');

        // ----------------------------
        // CREAR i EMAGATZEMAR VÍDEOS
        // ----------------------------
        Route::middleware('can:videos.create')->group(function () {
            Route::get('/videos/manage/create', [VideosManageController::class, 'create'])
                ->name('videos.manage.create');
            Route::post('/videos/manage', [VideosManageController::class, 'store'])
                ->name('videos.manage.store');
        });

        // --------------------------------------------------------
        // EDITAR, ACTUALITZAR I ESBORRAR VÍDEOS
        // --------------------------------------------------------
        Route::prefix('videos/manage')
            ->name('videos.manage.')
            ->group(function () {
                Route::get('/', [VideosManageController::class, 'index'])
                    ->middleware('can:manage-videos')
                    ->name('index');
                Route::get('/{id}/edit',    [VideosManageController::class, 'edit'])->name('edit');
                Route::put('/{id}',         [VideosManageController::class, 'update'])->name('update');
                Route::get('/{id}/delete',  [VideosManageController::class, 'delete'])->name('delete');
                Route::delete('/{id}',      [VideosManageController::class, 'destroy'])->name('destroy');
            });

        // --------------------------------------------------------
        // USUARIS MANAGEMENT (CRUD)
        // --------------------------------------------------------
        // Ruta per a la pàgina d'edició sense middlewares restrictius
        Route::get('/usersmanage/{id}/edit', [UsersManageController::class, 'edit'])
            ->name('users.manage.edit');

        // Resta de rutes de gestió d'usuaris amb middlewares
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

        // Rutes per crear i emmagatzemar sèries accessibles per qualsevol usuari autenticat
        Route::get('/seriesmanage/create', [SeriesManageController::class, 'create'])
            ->name('series.manage.create');
        Route::post('/seriesmanage', [SeriesManageController::class, 'store'])
            ->name('series.manage.store');

        // Rutes per a tots els usuaris autenticats (control d'accés dins del controlador)
        Route::prefix('seriesmanage')
            ->name('series.manage.')
            ->group(function () {
                Route::get('/', [SeriesManageController::class, 'index'])
                    ->middleware('can:manage-series')
                    ->name('index');
                Route::get('/{id}/edit', [SeriesManageController::class, 'edit'])->name('edit');
                Route::put('/{id}', [SeriesManageController::class, 'update'])->name('update');
                Route::get('/{id}/delete', [SeriesManageController::class, 'delete'])->name('delete');
                Route::delete('/{id}', [SeriesManageController::class, 'destroy'])->name('destroy');
            });
    });
