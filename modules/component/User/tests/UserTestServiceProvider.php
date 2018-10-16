<?php

namespace iBrand\Component\User\Test;

use Illuminate\Support\ServiceProvider;

class UserTestServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if($this->app->runningInConsole()){
			$this->registerMigrations();
		}
	}

	/**
	 * Register Passport's migration files.
	 */
	protected function registerMigrations()
	{
		return $this->loadMigrationsFrom(__DIR__.'/database');
	}

	/**
	 * Register the service provider.
	 */
	public function register()
	{
	}
}