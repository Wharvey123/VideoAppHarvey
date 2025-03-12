<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionHelper
{
    /** Defineix les portes d'accés per a la gestió de vídeos i usuaris. */
    public static function define_gates(): void
    {
        // Permet gestionar vídeos si l'usuari té el permís o és superadmin
        Gate::define('manage-videos', function ($user) {
            return $user->hasPermissionTo('manage videos') || $user->isSuperAdmin();
        });

        // Permet gestionar usuaris si l'usuari té el permís o és superadmin
        Gate::define('manage-users', function ($user) {
            return $user->hasPermissionTo('manage users') || $user->isSuperAdmin();
        });

        // Permisos més granulars per a usuaris (opcional)
        Gate::define('create-users', function ($user) {
            return $user->hasPermissionTo('users.create') || $user->isSuperAdmin();
        });

        Gate::define('edit-users', function ($user) {
            return $user->hasPermissionTo('users.edit') || $user->isSuperAdmin();
        });

        Gate::define('delete-users', function ($user) {
            return $user->hasPermissionTo('users.delete') || $user->isSuperAdmin();
        });
    }

    /** Crea els permisos necessaris per al CRUD de vídeos. */
    public static function create_permissions(): void
    {
        $permissions = [
            'manage videos',
            'videos.create',
            'videos.edit',
            'videos.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /** Crea els permisos necessaris per al CRUD d’usuaris. */
    public static function create_user_management_permissions(): void
    {
        $permissions = [
            'manage users',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
