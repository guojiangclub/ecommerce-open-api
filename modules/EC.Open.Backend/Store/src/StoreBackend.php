<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 2018/7/9
 * Time: 18:04
 */

namespace GuoJiangClub\EC\Open\Backend\Store;

use GuoJiangClub\EC\Open\Backend\Store\Seeds\StoreBackendTablesSeeder;
use Encore\Admin\Admin;
use Encore\Admin\Extension;
use Illuminate\Support\Facades\Artisan;

class StoreBackend extends Extension
{
    /**
     * Bootstrap this package.
     */
    public static function boot()
    {
        Admin::extend('ibrand-store-backend', __CLASS__);
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        Artisan::call('db:seed', ['--class' => StoreBackendTablesSeeder::class]);
    }
}