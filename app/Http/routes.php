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

$app->get('lokasi','LocationController@index');
$app->get('lokasi/{id}','LocationController@show');
$app->post('lokasi','LocationController@store');
$app->put('lokasi/{id}','LocationController@update');
$app->delete('lokasi/{id}','LocationController@destroy');

$app->get('user','UserController@index');
$app->get('user/{id}','UserController@show');
$app->post('user','UserController@store');
$app->put('user/{id}','UserController@update');
$app->delete('user/{id}','UserController@destroy');
$app->post('user/auth','UserController@login');

$app->get('draft','DraftController@index');
$app->get('draft/{id}','DraftController@show');
$app->post('draft','DraftController@store');
$app->put('draft/{id}','DraftController@update');
$app->delete('draft/{id}','DraftController@destroy');

$app->get('informasi','InformationController@index');
$app->get('informasi/{id}','InformationController@show');
$app->post('informasi','InformationController@store');
$app->put('informasi/{id}','InformationController@update');
$app->delete('informasi/{id}','InformationController@destroy');

$app->get('timbalbalik','FeedbackController@index');
$app->get('timbalbalik/{id}','FeedbackController@show');
$app->post('timbalbalik','FeedbackController@store');
$app->put('timbalbalik/{id}','FeedbackController@update');
$app->delete('timbalbalik/{id}','FeedbackController@destroy');

$app->get('skpd','SKPDController@index');
$app->get('skpd/{id}','SKPDController@show');
$app->post('skpd','SKPDController@store');
$app->put('skpd/{id}','SKPDController@update');
$app->delete('skpd/{id}','SKPDController@destroy');

$app->get('tag','TagController@index');
$app->get('tag/{id}','TagController@show');
$app->post('tag','TagController@store');
$app->put('tag/{id}','TagController@update');
$app->delete('tag/{id}','TagController@destroy');
