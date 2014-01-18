<?php
    define('ROOT_DIR', __DIR__);
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'test'));
    include_once ROOT_DIR . '/config/config.'.APPLICATION_ENV.'.php';

    require __DIR__.'/bootstrap/autoload.php';
    //fix
    require_once __DIR__.'/login_facebook/facebook.php';
    require_once __DIR__.'/app/libraries/credis/Client.php';

    $app = require_once __DIR__.'/bootstrap/start.php';

    $app->run();

    $app->shutdown();