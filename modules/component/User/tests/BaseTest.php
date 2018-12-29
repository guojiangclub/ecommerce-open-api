<?php

/*
 * This file is part of ibrand/balace.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\User\Test;

use iBrand\Component\User\Models\User;
use iBrand\Component\User\Models\UserBind;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use iBrand\Component\User\Repository\UserRepository;
use iBrand\Component\User\Repository\UserBindRepository;
use Orchestra\Testbench\TestCase;

/**
 * Class BaseTest.
 */
abstract class BaseTest extends TestCase
{
	use DatabaseMigrations;

	public $user;
	public $userBind;

	/**
	 * set up test.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->loadMigrationsFrom(__DIR__ . '/database');

		$this->user     = $this->app->make(UserRepository::class);
		$this->userBind = $this->app->make(UserBindRepository::class);

		$this->seedUser();
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
		]);
		$app['config']->set('repository.cache.enabled', true);
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			\Orchestra\Database\ConsoleServiceProvider::class,
			\iBrand\Component\User\UserServiceProvider::class,
		];
	}

	/**
	 * seed some balance.
	 */
	public function seedUser()
	{
		$user = User::create([
			'name'      => 'tangqi',
			'nick_name' => '承诺',
			'email'     => '923332947@qq.com',
			'mobile'    => '18684738715',
			'password'  => '123456',
			'status'    => '1',
			'sex'       => '男',
			'avatar'    => 'https://avatars1.githubusercontent.com/u/11305589?s=400&u=e05d3607f4347e3b739a995ea3b9d2374476627d&v=4',
			'city'      => '长沙市',
		]);

		UserBind::create([
			'type'      => UserBind::TYPE_WECHAT,
			'open_id'   => 'ovNizjmZxfWoVZ28vLdElWi8YsAw',
			'user_id'   => $user->id,
			'nick_name' => '承诺',
			'sex'       => '男',
			'email'     => '923332947@qq.com',
			'avatar'    => 'https://avatars1.githubusercontent.com/u/11305589?s=400&u=e05d3607f4347e3b739a995ea3b9d2374476627d&v=4',
			'app_id'    => 'wx49f648b10ef7f110b',
			'city'      => '长沙市',
		]);
	}
}