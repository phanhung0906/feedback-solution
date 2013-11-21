<?php
    define('ROOT_DIR', __DIR__);
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'test'));
    include_once ROOT_DIR . '/config/config.'.APPLICATION_ENV.'.php';
    if(Sesion::has('user')){
        if($_SERVER["REQUEST_URI"]=='/'){
            header('location:http://'.ROOT_URL.'/'.Session::get('user'));
        }
    }

    require __DIR__.'/bootstrap/autoload.php';

    $app = require_once __DIR__.'/bootstrap/start.php';


    $app->run();


    $app->shutdown();