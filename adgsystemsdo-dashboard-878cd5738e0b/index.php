<?php

require_once 'vendor/autoload.php';
require_once 'lib/array_group_by.php';

use Symfony\Component\HttpFoundation\Request as SRequest;
use App\Http\Request;
use Manager\Common\Auth;
use App\Services\Mailer;

/**
 * Loading enviorement configuration
 */
try {
    \App\Common\Config::initialize('env.json');
} catch (\Noodlehaus\Exception $exception) {
    echo $exception->getMessage();
    die();
}

/**
 * Enable or disable error reporting depending on config
 */
if (\App\Common\Config::get('mode') == 'debug') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/**
 * Making sessions work with manager and legacy code
 */
ini_set('session.save_handler', 'files');
ini_set('session.save_path', '/tmp');
session_start();

/**
 * Build request param bag
 */
Request::createRequest(SRequest::createFromGlobals());

/**
 * Application routing
 */
$router = new \Bramus\Router\Router();

$router->post('/hosts','App\Controllers\HostsController@getHost');

$router->match('GET|POST','/', function() {
    Header('Location: /login');
});

$router->get('/public/twofactortutorial', 'App\Controllers\PagesController@twoFactor');

$router->get('/adminsettings', 'App\Controllers\SettingsController@index');

$router->post('/adminsettings', 'App\Controllers\SettingsController@saveSettings');

$router->get('/userdevices/details/(.*)','App\Controllers\DeviceDetailsController@getUserUpdatesDetails');

$router->get('/serialnumbers','App\Controllers\DeviceDetailsController@serials');
$router->post('/serialnumbers','App\Controllers\DeviceDetailsController@serialsUpdate');
$router->get('/users/list','App\Controllers\DeviceDetailsController@users');

$router->get('/userdevices','App\Controllers\DeviceDetailsController@getUserDevices');
$router->get('/clienthost','App\Controllers\DeviceDetailsController@getclienthost');

$router->get('/modules','App\Controllers\ModulesController@index');

$router->get('/test', function () {
    $mailer = new Mailer();
    try {
        $mailer->send("elnelsonperez@gmail.com", "HOla", "Hola");
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});

/**
 * Load individual route files
 */
$routeFiles = ['auth','dashboard','reports','manager','legacy'];

foreach ($routeFiles as $filename) {
    $path = 'app/Routes/' . $filename. '.php';
    if (is_file($path)) {
        require_once $path;
    }
}

/**
 * Before middleware
 */
$router->before('GET|POST', '/manager/(.*)', function($route) {
    /**
     * Build authentication class
     */
    Auth::initialize();
    if ($route !== 'login') {
        if (!Auth::check()) {
            return (new \App\Common\BlankRedirectResponse('/manager/login'))->send();
        }
    } else {
        if (Auth::check()) {
            return (new \App\Common\BlankRedirectResponse('/manager/dashboard'))->send();
        }
    }
    return true;
});

$router->before('GET|POST', '/(.*)', function($route) {
    $base = explode('/',$route)[0];

    if ($base !== 'manager' &&
        $base !== 'hosts' &&
        $base !== 'securelogin' &&
        $base !== 'public' &&
        $base !== 'dashboard'
    ) {
        if (!isset($_SESSION['user_id'])){
            if ( $route !== 'login') {
                Header('Location: /login');
            }
        } else {
            if ( $route == 'login') {
                Header('Location: /dashboard');
            }
        }
    }
});

//Default No Match page
$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    loadLegacyView('404');
});

$router->run();
