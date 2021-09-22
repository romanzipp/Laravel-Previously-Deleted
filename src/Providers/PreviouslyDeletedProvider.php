<?php

namespace romanzipp\PreviouslyDeleted\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use romanzipp\PreviouslyDeleted\Rules\NotPreviouslyDeleted;

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
            dirname(__DIR__) . '/../config/previously-deleted.php' => config_path('previously-deleted.php'),
        ], 'config');

        $this->loadMigrationsFrom(
            dirname(__DIR__) . '/../migrations'
        );

        Validator::extend('not_previously_deleted', $callback = function ($attribute, $value, $parameters) {
            return (new NotPreviouslyDeleted(...$parameters))->passes($attribute, $value);
        }, config('previously-deleted.failed_message'));

        Validator::extend('not_deleted', $callback, config('previously-deleted.failed_message'));
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../config/previously-deleted.php',
            'previously-deleted'
        );
    }
}
