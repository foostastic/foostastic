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

$app->get('/google', function () use ($app) {
    return view('googleAuth');
});
$app->get('/check', function () use ($app) {
    return $app->version();
});

$app->get('/', 'HomeController@index');
$app->get('/ranking', 'HomeController@ranking');
$app->get('/login', 'HomeController@login');
$app->get('/loginCallback', 'HomeController@loginCallback');
$app->get('/fakeLogin', 'HomeController@fakeLoginAction');
$app->get('/logout', 'HomeController@logoutAction');
$app->get('/account', 'HomeController@account');
$app->post('/buy', 'HomeController@buyAction');
$app->post('/sell', 'HomeController@sellAction');
$app->get('/onMatchFinished', 'ObserverController@onMatchFinished');
