<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Providers;
use GuoJiangClub\EC\Open\Backend\Store\Console\InstallCommand;
use GuoJiangClub\EC\Open\Backend\Store\Console\SetDefaultValueCommand;
use GuoJiangClub\EC\Open\Backend\Store\Console\SpecCommand;
use GuoJiangClub\EC\Open\Backend\Store\Model\Product;
use GuoJiangClub\EC\Open\Backend\Store\Observers\ProductObserver;
use GuoJiangClub\EC\Open\Backend\Store\StoreBackend;
use iBrand\UEditor\UEditorServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use GuoJiangClub\EC\Open\Backend\Store\Service\GoodsService;
use GuoJiangClub\EC\Open\Backend\Store\Service\OrderService;
use GuoJiangClub\EC\Open\Backend\Store\Service\ExcelExportsService;
use GuoJiangClub\EC\Open\Backend\Store\Service\DiscountService;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'GuoJiangClub\EC\Open\Backend\Store\Http\Controllers';

    
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //publish a config file
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('ibrand/store.php'),
        ]);

        if (!$this->app->routesAreCached()) {
            $this->mapWebRoutes();
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'store-backend');

        StoreBackend::boot();

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../resources/assets' => public_path('assets/backend'),
                __DIR__ . '/../../resources/assets/template' => public_path('assets/template'),
                __DIR__ . '/../../resources/assets/libs/sweetalert' => public_path('vendor/laravel-admin/sweetalert'),
            ], 'store-backend-assets');
        }

        $this->commands([
            InstallCommand::class,
            SpecCommand::class,
            SetDefaultValueCommand::class
        ]);

        Product::observe(ProductObserver::class);

        $this->mergeUeditorConfig();
    }

    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config.php', 'ibrand.store'
        );


        $this->app->register(\GuoJiangClub\EC\Open\Backend\Member\Providers\BackendServiceProvider::class);
        $this->app->register(UEditorServiceProvider::class);

        $this->app->singleton('GoodsService', function () {
            return new  GoodsService();
        });

        $this->app->singleton('OrderService', function () {
            return new  OrderService();
        });

        $this->app->singleton('ExcelExportsService', function () {
            return new  ExcelExportsService();
        });

        $this->app->singleton('DiscountService', function () {
            return new  DiscountService();
        });

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => ['web', 'admin'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require __DIR__ . '/../Http/routes.php';
        });
    }

    public function provides()
    {
        return ['GoodsService', 'OrderService', 'ExcelExportsService', 'DiscountService', 'RefundService', 'RegistrationsService'];
    }

    private function mergeUeditorConfig()
    {
        // merge config
        $config = $this->app['config']->get('UEditorUpload', []);

        $config['upload']['imageUrlPrefix'] = url('/');
        $config['upload']['fileUrlPrefix'] = url('/');

        $this->app['config']->set('UEditorUpload', $config);
    }
}
