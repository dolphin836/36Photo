<?php

define('VERSIONR', '1.0.0');

define('BASEPATH', __DIR__);
define('ROOTPATH', BASEPATH . DIRECTORY_SEPARATOR . '..'    . DIRECTORY_SEPARATOR);
define('COREPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'core'  . DIRECTORY_SEPARATOR);
define('APPSPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'app'   . DIRECTORY_SEPARATOR);
define('CACHPATH', ROOTPATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);

date_default_timezone_set('PRC');

require ROOTPATH . 'vendor/autoload.php';

$env = new Dotenv\Dotenv(ROOTPATH);
$env->load();

$set = include COREPATH . 'config.php';
$app = new \Slim\App($set);

require COREPATH . 'dependencies.php';
require COREPATH . 'middleware.php';
require COREPATH . 'routes.php';

$app->run();