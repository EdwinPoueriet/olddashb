<?php

$router->get('/manager', function () {
    return (new \Symfony\Component\HttpFoundation\RedirectResponse('/manager/login'))->send();
});

$router->get('/manager/login','\Manager\Controllers\AuthController@login');

$router->get('/manager/seriales','\Manager\Controllers\SerialesController@index');
$router->post('/manager/seriales/borrar','\Manager\Controllers\SerialesController@borrar');
$router->post('/manager/seriales/generar','\Manager\Controllers\SerialesController@generar');

$router->get('/manager/dashboard','\Manager\Controllers\ClientsController@index');
$router->get('/manager/clients','\Manager\Controllers\ClientsController@index');
$router->post('/manager/clients','\Manager\Controllers\ClientsController@createClient');
$router->post('/manager/clients/associate','\Manager\Controllers\ClientsController@associateClient');
$router->get('/manager/users/credentials', '\Manager\Controllers\CredentialsController@index');
$router->post('/manager/users/credentials', '\Manager\Controllers\CredentialsController@saveCredentials');
$router->get('/manager/scripts','\Manager\Controllers\ScriptsController@index');
$router->get('/manager/users','\Manager\Controllers\UsersController@index');
$router->get('/manager/users/edit','\Manager\Controllers\UsersController@getUserData');
$router->post('/manager/users/edit','\Manager\Controllers\UsersController@editUser');
$router->post('/manager/users','\Manager\Controllers\UsersController@createUser');
$router->post('/manager/users/delete','\Manager\Controllers\UsersController@deleteUser');
$router->post('/manager/scripts/databasecode','\Manager\Controllers\ScriptsController@saveDatabaseCode');
$router->post('/manager/login','\Manager\Controllers\AuthController@validate');
