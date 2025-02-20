<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionHelper
{
    /** Defineix les portes d'accés per a la gestió de vídeos. */
    public static function define_gates(): void
    {
        Gate::define('manage-videos', function ($user) {
            // Permet gestionar vídeos si l'usuari té el permís o és superadmin
            return $user->hasPermissionTo('manage videos') || $user->isSuperAdmin();
        });
    }

    /** Crea els permisos necessaris per al CRUD de vídeos. */
    public static function create_permissions(): void
    {
        // Llista de permisos necessaris per gestionar els vídeos
        $permissions = [
            'manage videos',
            'videos.create',
            'videos.edit',
            'videos.delete',
        ];

        // Per a cada permís, crea'l si no existeix
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
