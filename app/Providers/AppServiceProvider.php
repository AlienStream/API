<?php namespace API\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'API\Services\Registrar'
		);

		$this->app->bind(
			'API\Repositories\Boundary\AbstractRepository',
			'API\Repositories\Implementation\EloquentAbstractRepository'
		);

		$this->app->bind(
			'API\Repositories\Boundary\UserRepository',
			'API\Repositories\Implementation\EloquentUserRepository'
		);
	}

}
