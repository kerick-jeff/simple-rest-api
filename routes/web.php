<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['middleware' => 'auth.api'], function() use ($router) {

	$router->get('/', function () use ($router) {
	    return $router->app->version();
	});

	// API Endpoints: Version 1
	$router->group(['prefix' => 'v1'], function () use ($router) {

		// Authentication Endpoints
		$router->group(['prefix' => 'auth'], function () use ($router) {
			$router->post('login', 'AuthController@login');
			$router->post('register', 'AuthController@register');
		});

		// Post Endpoints
		$router->group(['prefix' => 'posts'], function () use ($router) {
			$router->get('/', 'PostController@getAll');
			$router->get('{id:\d+}', 'PostController@getById');
			$router->get('{slug:[a-z0-9-]+}', 'PostController@getBySlug');

			// Only authenticated users can access these post endpoints
			$router->group(['middleware' => 'auth'], function () use ($router) {
				$router->post('/', 'PostController@store');
				$router->put('{id:\d+}', 'PostController@update');
				$router->delete('{id:\d+}', 'PostController@remove');
			});
		});

		// User Endpoints
		$router->group(['prefix' => 'users'], function () use ($router) {
			$router->get('/', 'UserController@getAll');
			$router->get('{id:\d+}', 'UserController@getById');
			$router->get('{slug:[a-z0-9-]+}', 'UserController@getBySlug');

			// Only authenticated users can access these user endpoints
			$router->group(['middleware' => 'auth'], function () use ($router) {
				$router->put('update', 'UserController@update');
				$router->delete('delete', 'UserController@remove');
			});
		});

	});
	
});