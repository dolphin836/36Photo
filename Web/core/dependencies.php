<?php

use Slim\Http\Response;
use GuzzleHttp\Psr7;

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

// view renderer
$container['template'] = function ($c) {
    if (getenv('DEBUG') == 'TRUE') {
        $loader = new Twig_Loader_Filesystem('../app/Template');
        $debug  = true;
    } else {
        $loader = new Twig_Loader_Filesystem('../app/View');
        $debug  = false;
    }

    return new Twig_Environment($loader, array(
                   'debug' => $debug,
                   'cache' => CACHPATH,
             'auto_reload' => true,
        'strict_variables' => $debug
    ));
};

// 自定义 404 页面
unset($container['notFoundHandler']);

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        if (getenv('DEBUG') == 'TRUE') {
            $not_found_template = '../app/Template/NotFound.html';
        } else {
            $not_found_template = '../app/View/NotFound.html';
        }

        $stream   = new Psr7\LazyOpenStream($not_found_template, 'r');
        $response = new Response(404);

        return $response->withBody($stream);
    };
};