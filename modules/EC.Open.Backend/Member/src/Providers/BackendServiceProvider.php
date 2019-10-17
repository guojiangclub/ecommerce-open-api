<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Backend\Member\Providers;

use GuoJiangClub\EC\Open\Backend\Member\MemberBackend;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Menu;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'GuoJiangClub\EC\Open\Backend\Member\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            $this->mapWebRoutes();
        }

        MemberBackend::boot();

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'member-backend');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/assets/template' => public_path('assets/template'),
            ], 'member-backend-assets');

            $this->publishes([
                __DIR__.'/../../resources/assets/libs' => public_path('assets/backend/libs'),
            ], 'member-backend-assets-libs');

            $this->publishes([
                __DIR__.'/../../resources/assets/css' => public_path('assets/backend/css'),
            ], 'member-backend-assets-css');

            $this->publishes([
                __DIR__.'/../../resources/assets/images' => public_path('assets/backend/images'),
            ], 'member-backend-assets-images');
            
        }
    }

    public function register()
    {

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => ['web', 'admin'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require __DIR__.'/../Http/routes.php';
        });
    }

}
