<?php

/*
 * This file is part of ibrand/user.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\User;

use GuoJiangClub\Component\User\Repository\Eloquent\UserBindRepositoryEloquent;
use GuoJiangClub\Component\User\Repository\Eloquent\UserRepositoryEloquent;
use GuoJiangClub\Component\User\Repository\UserBindRepository;
use GuoJiangClub\Component\User\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (!class_exists('CreateUserTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_user_tables.php.stub' => database_path()."/migrations/{$timestamp}_create_user_tables.php",
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserBindRepository::class, UserBindRepositoryEloquent::class);
    }
}
