<?php

$router->get('/dashboard', 'App\Controllers\DashboardController@index');
$router->get('/dashboard/fetchventasdata', 'App\Controllers\DashboardController@fetchVentasDataAsync');
$router->get('/dashboard/fetchcotizacionesdata', 'App\Controllers\DashboardController@fetchCotizacionesDataAsync');
$router->get('dashboard/fetchingresosdata', 'App\Controllers\DashboardController@fetchIngresosDataAsync');
$router->get('dashboard/fetchefectividaddata', 'App\Controllers\DashboardController@fetchEfectividadDataAsync');

$router->before('GET', '/dashboard/?(.*)', function($route) {
        if (!isset($_SESSION['user_id'])){
            if ($route == '') {
                return (new \App\Common\BlankRedirectResponse('/login'))->send();
            }
            return ( new\App\Common\BlankRedirectResponse('NOT LOGGED IN',403))->send();
        }
});
