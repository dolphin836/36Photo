<?php

$app->get('/login', 'Dolphin\Ting\Controller\Login');

$app->get('/', 'Dolphin\Ting\Controller\Home');
// 添加用户
$app->get('/user/add', 'Dolphin\Ting\Controller\User\GetAdd');
$app->post('/user/add', 'Dolphin\Ting\Controller\User\PostAdd');
// 用户列表
// $app->get('/user/records[/{page}]', "Dolphin\Ting\Controller\User\GetRecords");
$app->get('/user/records', "Dolphin\Ting\Controller\User\GetRecords");
// 删除用户
$app->get('/user/delete/{uuid}', 'Dolphin\Ting\Controller\User\GetDelete');
// 404
$app->get('/404', 'Dolphin\Ting\Controller\NotFound');


// $params = $container->request->getUri()->getPath();

// $method = $container->request->getMethod();

// $method = strtolower($method);

// $path = explode('/', $params);

// $c = isset($path[1]) && $path[1] != '' ? $path[1] : 'home';
// $m = isset($path[2]) && $path[1] != '' ? $path[2] : 'index';

// $app->$method($params, "Dolphin\Ting\Controller" . "\\" . ucwords($c) . "\\" . ucwords($method) . ucwords($m));

