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
Route::get('me', 'UserController@me');


Route::get('track', 'TrackController@index');
Route::get('track/trending', 'TrackController@trending');
Route::get('track/popular', 'TrackController@popular');
Route::get('track/newest', 'TrackController@newest');
Route::get('track/{id}', 'TrackController@byId');
Route::post('track/{id}/favorite', 'TrackController@favorite');
Route::post('track/{id}/flag', 'TrackController@flag');

Route::get('artist', 'ArtistController@index');
Route::get('artist/trending', 'ArtistController@trending');
Route::get('artist/popular', 'ArtistController@popular');
Route::get('artist/newest', 'ArtistController@newest');
Route::get('artist/{id}', 'TrackController@byId');

Route::get('genres', 'GenreController@index');
Route::get('genres/{name}/artists', 'ArtistController@byGenre');
Route::get('genres/{name}/communities', 'CommunityController@byGenre');

Route::get('communities', 'CommunityController@index');
Route::get('communities/trending', 'CommunityController@trending');
Route::get('communities/popular', 'CommunityController@popular');
Route::get('communities/newest', 'CommunityController@newest');
Route::get('community/{name}', 'CommunityController@byName');
Route::get('community/{name}/tracks', 'TrackController@byCommunity');
Route::post('communities/{name}', 'CommunityController@create');
Route::put('communities/{name}', 'CommunityController@update');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
