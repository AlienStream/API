<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('track', 'TrackController@index');

Route::get('communities', 'CommunityController@index');
Route::get('communities/trending', 'CommunityController@trending');
Route::get('communities/popular', 'CommunityController@popular');
Route::get('communities/newest', 'CommunityController@newest');
Route::get('community/{name}', 'CommunityController@byName');
Route::get('community/{name}/tracks', 'CommunityController@byName_Tracks');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
