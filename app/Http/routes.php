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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('location','LocationController@index');
$app->get('location/{id}','LocationController@show');
$app->post('location','LocationController@store');
$app->put('location/{id}','LocationController@update');
$app->delete('location/{id}','LocationController@destroy');


$app->get('user','UserController@index');
$app->get('user/{id}','UserController@show');
$app->post('user','UserController@store');
$app->put('user/{id}','UserController@update');
$app->delete('user/{id}','UserController@destroy');
