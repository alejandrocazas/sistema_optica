<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    // Forzar HTTPS en producción para que carguen los estilos
    if($this->app->environment('production')) {
        URL::forceScheme('https');
    }
    }
    // --- NUEVO: AUTO-REPARACIÓN DE FOTOS ---
            // Si el enlace simbólico no existe, lo creamos automáticamente.
            if (!file_exists(public_path('storage'))) {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            }
}
