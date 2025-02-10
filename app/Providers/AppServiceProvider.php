<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Helpers\PermissionHelper;

class AppServiceProvider extends ServiceProvider
{
    /** Registra les polítiques d'autorització. */
    public function boot(): void
    {
        // Registra les polítiques, si n'hi ha
        $this->registerPolicies();

        // Defineix les portes d'accés
        PermissionHelper::define_gates();
    }
}
