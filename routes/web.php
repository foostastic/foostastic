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

$app->get('/auth/google', 'auth\AuthController@redirectToGoogle');
$app->get('auth/google/callback', 'auth\AuthController@handleGoogleCallback');

$app->get('/', function () use ($app) {
    $results = app('db')->select("SELECT * FROM users");
    var_dump($results);
    return $app->version();
});
