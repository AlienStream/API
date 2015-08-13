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
Route::group(['middleware' => 'auth'], function () {
	Route::get('me', 'UserController@me');
});


Route::get('tracks', 'TrackController@index');
Route::get('tracks/trending', 'TrackController@trending');
Route::get('tracks/popular', 'TrackController@popular');
Route::get('tracks/newest', 'TrackController@newest');
Route::get('track/{id}', 'TrackController@byId');
Route::group(['middleware' => 'auth'], function () {
	Route::post('track/{id}/favorite', 'TrackController@favorite');
	Route::post('track/{id}/flag', 'TrackController@flag');
});

Route::get('artists', 'ArtistController@index');
Route::get('artists/trending', 'ArtistController@trending');
Route::get('artists/popular', 'ArtistController@popular');
Route::get('artists/newest', 'ArtistController@newest');
Route::get('artist/{id}', 'ArtistController@byId');
Route::group(['middleware' => 'auth'], function () {
	Route::post('artist/{id}/favorite', 'artistController@favorite');
});

Route::get('genres', 'GenreController@index');
Route::get('genre/{id}', 'GenreController@byId');

Route::get('communities', 'CommunityController@index');
Route::get('communities/trending', 'CommunityController@trending');
Route::get('communities/popular', 'CommunityController@popular');
Route::get('communities/newest', 'CommunityController@newest');
Route::get('community/{name}', 'CommunityController@byName');
Route::get('community/{name}/tracks', 'TrackController@byCommunity');
Route::group(['middleware' => 'auth'], function () {
	Route::post('community/{name}', 'CommunityController@create');
	Route::post('community/{name}', 'CommunityController@favorite');
	Route::put('community/{name}', 'CommunityController@update');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('user/{id}/favorited_communities', 'UserController@favoritedCommunities');
	Route::get('user/{id}/moderated_communities', 'UserController@moderatedCommunities');
});


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
