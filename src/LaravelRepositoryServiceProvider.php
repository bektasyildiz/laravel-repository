<?php

namespace Bektasyildiz\LaravelRepository;

use Bektasyildiz\LaravelRepository\Commands\MakeRepository;
use Illuminate\Support\ServiceProvider;

class LaravelRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/config.php' => config_path('laravel-repository.php'),
                ],
                'config',
            );

            $this->commands([MakeRepository::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-respository');

        // Register the main class to use with the facade
        $this->app->singleton('laravelrepository', function () {
            return new LaravelRepository();
        });
    }
}
