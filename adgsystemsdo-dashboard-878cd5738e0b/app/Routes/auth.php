<?php
$router->post('/login', 'App\Controllers\AuthController@usernameAndPasswordLogin');
$router->get('/login', 'App\Controllers\PagesController@getLogin');

$router->get('/securelogin', 'App\Controllers\AuthController@secureLogin');
$router->post('/securelogin', 'App\Controllers\AuthController@validateKey');


$router->post('/public/selectclient', 'App\Controllers\AuthController@selectClient');
$router->get('/public/authorizeaccess', 'App\Controllers\AuthController@validateToken');
