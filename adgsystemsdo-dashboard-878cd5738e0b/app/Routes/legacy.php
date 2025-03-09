<?php

//Legacy
$router->post('/legacy/general', function() {
    loadLegacyFunc('General');
});

$router->match('GET|POST','/legacy/ajax/(\w+)',function ($sub) {
    $file = 'ajax/'.$sub;
    if (file_exists($file.'.php')) {
        loadLegacyView($file);
    }
});

$router->match('GET|POST','/legacy/print/(\w+)',function ($sub) {
    $file = 'print/'.$sub;
    if (file_exists($file.'.php')) {
        loadLegacyView($file);
    }
});

$router->match('GET|POST','[^/]+',function () {
    $file = str_replace('/','',$_SERVER['REQUEST_URI']);
    $ext = '';
    if (!str_contains($file,'.php')) {
        $ext = '.php';
    }
    if (file_exists($file.$ext)) {
        loadLegacyView($file);
    } else {
        loadLegacyView('404');
    }
});


function loadLegacyView ($filename, $basedir = null) {

    if (!$basedir) {
        require $filename.'.php';
    } else
        require $basedir.$filename.'.php';
}

function loadLegacyFunc ($filename) {
    loadLegacyView($filename,'config/');
}