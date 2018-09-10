<?php

namespace romanzipp\PreviouslyDeleted\Providers;

use Illuminate\Support\ServiceProvider;

class PreviouslyDeletedProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/../previously-deleted.php' => config_path('previously-deleted.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../previously-deleted.php', 'previously-deleted'
        );
    }
}
