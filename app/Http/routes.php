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

$app->get('draft','DraftController@index');
$app->get('draft/{id}','DraftController@show');
$app->post('draft','DraftController@store');
$app->put('draft/{id}','DraftController@update');
$app->delete('draft/{id}','DraftController@destroy');

$app->get('information','InformationController@index');
$app->get('information/{id}','InformationController@show');
$app->post('information','InformationController@store');
$app->put('information/{id}','InformationController@update');
$app->delete('information/{id}','InformationController@destroy');

$app->get('feedback','FeedbackController@index');
$app->get('feedback/{id}','FeedbackController@show');
$app->post('feedback','FeedbackController@store');
$app->put('feedback/{id}','FeedbackController@update');
$app->delete('feedback/{id}','FeedbackController@destroy');

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
