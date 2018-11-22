<?php

namespace iBrand\EC\Open\Backend\Store\Providers;
use iBrand\EC\Open\Backend\Store\Console\InstallCommand;
use iBrand\EC\Open\Backend\Store\Console\SetDefaultValueCommand;
use iBrand\EC\Open\Backend\Store\Console\SpecCommand;

use iBrand\EC\Open\Backend\Store\Listeners\LogSuccessfulLoginListener;
use iBrand\EC\Open\Backend\Store\Model\Product;
use iBrand\EC\Open\Backend\Store\Observers\ProductObserver;
use iBrand\EC\Open\Backend\Store\StoreBackend;
use iBrand\UEditor\UEditorServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use iBrand\EC\Open\Backend\Store\Service\GoodsService;
use iBrand\EC\Open\Backend\Store\Service\OrderService;
use iBrand\EC\Open\Backend\Store\Service\ExcelExportsService;
use iBrand\EC\Open\Backend\Store\Service\DiscountService;
use iBrand\EC\Open\Backend\Store\Service\RefundService;
use Menu;
use Event;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'iBrand\EC\Open\Backend\Store\Http\Controllers';

    /**
     * 要注册的订阅者类。
     *
     * @var array
     */
    protected $subscribe = [
        'iBrand\EC\Open\Backend\Store\Listeners\PromotionEventListener'
    ];

    protected $listen = [
        Login::class => [
            LogSuccessfulLoginListener::class,
        ],
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        if (!app()->runningInConsole()) {

            foreach ($this->subscribe as $item) {
                Event::subscribe($item);
            }
        }

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }

        //publish a config file
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('store.php'),
            __DIR__ . '/../dmp.php' => config_path('dmp.php')
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

            $this->registerMigrations();
        }

        $this->commands([
            InstallCommand::class,
            SpecCommand::class,
            SetDefaultValueCommand::class
        ]);

        $this->registerMenu();

        Product::observe(ProductObserver::class);

        $this->mergeUeditorConfig();
    }

    public function register()
    {

        $this->app->register(\iBrand\EC\Open\Backend\Member\Providers\BackendServiceProvider::class);
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

        $this->app->singleton('RefundService', function () {
            return new  RefundService();
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

    private function registerMenu()
    {
        Menu::make('topMenu', function ($menu) {

            $menu->add('<i class="iconfont icon-shangchengguanli-"></i>
                            <span>商城管理</span>', ['url' => 'admin/store', 'secure' => env('SECURE')])
                ->active('admin/store/*')
                ->active('admin/image/*')
                ->active('admin/promotion/*')
                ->active('admin/order/*')
                ->active('admin/refund/*')
                ->active('admin/comments/*')
                ->active('admin/shippingmethod/*');
        });
    }

    public function provides()
    {
        return ['GoodsService', 'OrderService', 'ExcelExportsService', 'DiscountService', 'RefundService', 'RegistrationsService'];
    }

    protected function registerMigrations()
    {
        return $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
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
