<?php
namespace GuoJiangClub\EC\Open\Backend\Album\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Menu;
use Event;

class AlbumBackendServiceProvider extends ServiceProvider
{
    protected $namespace = 'GuoJiangClub\EC\Open\Backend\Album\Http\Controllers';


    /**
     * Boot the provider.
     */
    public function boot()
    {
        $this->loadConfig();

        if (!$this->app->routesAreCached()) {
            $this->mapWebRoutes();
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'file-manage');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../resources/assets' => public_path('assets/backend/file-manage')
            ], 'file-manage-assets');

            $this->registerMigrations();
        }

        Event::subscribe('GuoJiangClub\EC\Open\Backend\Album\Listeners\UploadListeners');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'file-manage'
        );
    }

    /**
     * Register config.
     */
    protected function loadConfig()
    {
        $this->publishes([
            __DIR__.'/../config.php' => config_path('ibrand/file-manage.php'),
        ]);
    }

    /**
     * Register routes.
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => ['web'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require __DIR__ . '/../Http/routes.php';
        });
    }

    /**
     * Register migrations
     */
    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
    }

}