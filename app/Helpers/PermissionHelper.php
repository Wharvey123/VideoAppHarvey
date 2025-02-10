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

    /** Crea els permisos necessaris. */
    public static function create_permissions(): void
    {
        Permission::firstOrCreate(['name' => 'manage videos']);
    }
}
