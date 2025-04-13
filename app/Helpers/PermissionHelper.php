<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHelper
{
    /** Defineix totes les portes d'accés (Gates) relacionades amb la gestió de sèries, vídeos i usuaris.*/
    public static function define_gates(): void
    {
        // Gestions generals
        Gate::define('manage-series', fn($user) => $user->hasPermissionTo('manage series') || $user->isSuperAdmin());
        Gate::define('manage-videos', fn($user) => $user->hasPermissionTo('manage videos') || $user->isSuperAdmin());
        Gate::define('manage-users', fn($user) => $user->hasPermissionTo('manage users') || $user->isSuperAdmin());

        // Permisos específics d'usuaris
        Gate::define('create-users', fn($user) => $user->hasPermissionTo('users.create') || $user->isSuperAdmin());
        Gate::define('edit-users', fn($user) => $user->hasPermissionTo('users.edit') || $user->isSuperAdmin());
        Gate::define('delete-users', fn($user) => $user->hasPermissionTo('users.delete') || $user->isSuperAdmin());

        // Permisos específics de sèries
        Gate::define('create-series', fn($user) =>
            $user->hasPermissionTo('series.create') || $user->hasPermissionTo('manage series') || $user->isSuperAdmin()
        );
        Gate::define('edit-series', fn($user) =>
            $user->hasPermissionTo('series.edit') || $user->hasPermissionTo('manage series') || $user->isSuperAdmin()
        );
        Gate::define('delete-series', fn($user) =>
            $user->hasPermissionTo('series.delete') || $user->hasPermissionTo('manage series') || $user->isSuperAdmin()
        );
    }

    /** Crea els permisos bàsics per a la gestió de vídeos (CRUD).*/
    public static function create_permissions(): void
    {
        $permissions = ['manage videos', 'videos.create', 'videos.edit', 'videos.delete',];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /** Crea els permisos bàsics per a la gestió d'usuaris (CRUD).*/
    public static function create_user_management_permissions(): void
    {
        $permissions = ['manage users', 'users.create', 'users.edit', 'users.delete', 'view users',];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Crea els permisos per a la gestió de sèries i vídeos, i els assigna a rols específics.
     * El rol 'superadmin' té tots els permisos.
     * El rol 'videomanager' només té permisos de sèries.
     */
    public static function create_series_management_permissions(): void
    {
        $seriesPermissions = ['manage series', 'series.create', 'series.edit', 'series.delete',];
        $videoPermissions = ['manage videos', 'videos.create', 'videos.edit', 'videos.delete',];

        // Crear permisos si no existeixen
        foreach (array_merge($seriesPermissions, $videoPermissions) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assignar permisos al rol superadmin
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->syncPermissions(array_merge($seriesPermissions, $videoPermissions));

        // Assignar només permisos de sèries al rol videomanager
        $videomanagerRole = Role::firstOrCreate(['name' => 'videomanager']);
        $videomanagerRole->syncPermissions($seriesPermissions);
    }
}
