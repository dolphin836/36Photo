<?php

$container = $app->getContainer();

// db
$container['db'] = function($c) {
    return new Medoo\Medoo([
        'database_type' => 'mysql',
        'database_name' => getenv('DB_NAME'),
               'server' => getenv('DB_HOST'),
             'username' => getenv('DB_USERNAME'),
             'password' => getenv('DB_PASSWORD'),
              'charset' => 'utf8',
    ]);
};

// HTTP
$container['guzzle'] = function ($c) {
    return new \GuzzleHttp\Client();
};

// view renderer
$container['template'] = function ($c) {
    $loader = new Twig_Loader_Filesystem('../app/Template');

    if (getenv('DEBUG') == 'TRUE') {
        $debug  = true;
    } else {
        $debug  = false;
    }

    return new Twig_Environment($loader, array(
                   'debug' => $debug,
                   'cache' => CACHPATH,
             'auto_reload' => true,
        'strict_variables' => $debug
    ));
};
// CSRF
$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard('next');
    // 检查失败的回调
    // $guard->setFailureCallable(function ($request, $response, $next) {
    //     // $request = $request->withAttribute("csrf_status", false);
    //     return $next($request, $response);
    // });

    return $guard;
};
// Flash Message
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};