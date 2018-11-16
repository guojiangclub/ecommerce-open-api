<?php

/*
 * This file is part of ibrand/member-backend.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Backend\Member\Providers;

use iBrand\EC\Open\Backend\Member\Console\RolesCommand;
use iBrand\EC\Open\Backend\Member\MemberBackend;
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
    protected $namespace = 'iBrand\EC\Open\Backend\Member\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            $this->mapWebRoutes();
        }

        MemberBackend::boot();

        $this->commands([
            RolesCommand::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'member-backend');
        $this->registerMigrations();
        $this->registerMenu();

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

    private function registerMenu()
    {
        Menu::make('topMenu', function ($menu) {
            $menu->add('<i class="iconfont icon-huiyuanguanli-"></i>
                            <span>会员管理</span>', ['url' => 'admin/member/users', 'secure' => env('SECURE')])->active('admin/member/*')->active('admin/manager*');
        });
    }

    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__.'/../../migrations');
    }
}
