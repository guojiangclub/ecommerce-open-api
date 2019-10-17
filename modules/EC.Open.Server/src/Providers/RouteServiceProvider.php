<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Providers;

use Dingo\Api\Transformer\Adapter\Fractal;
use GuoJiangClub\EC\Open\Server\Serializer\DataArraySerializer;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use League\Fractal\Manager;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'GuoJiangClub\EC\Open\Server\Http\Controllers';

    public function boot()
    {
        parent::boot();

        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new Manager();

            $fractal->setSerializer(new DataArraySerializer());

            return new Fractal($fractal);
        });
    }

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes()
    {
        $api = app('Dingo\Api\Routing\Router');

        $api->version('v1',
            array_merge(config('ibrand.ec-open-api.routeAttributes'), ['namespace' => $this->namespace]), function ($router) {
                require __DIR__.'/../routes/api.php';
            });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/../routes/web.php');
    }
}
