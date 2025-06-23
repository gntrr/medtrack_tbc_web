<?php

namespace App\Providers;

use App\Services\WhatsAppService;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WhatsAppService::class, function ($app) {
            return new WhatsAppService();
        });
        
        // Buat alias untuk service
        $this->app->alias(WhatsAppService::class, 'whatsapp');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish konfigurasi
        $this->publishes([
            __DIR__.'/../../config/whatsapp.php' => config_path('whatsapp.php'),
        ], 'config');
    }
}
