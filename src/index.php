<?php
    define('ROOT_DIR', __DIR__);
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'test'));
    include_once ROOT_DIR . '/config/config.'.APPLICATION_ENV.'.php';

    require __DIR__.'/bootstrap/autoload.php';

    $app = require_once __DIR__.'/bootstrap/start.php';


    $app->run();


    $app->shutdown();