<?php

// // 添加用户
// $app->get('/user/add', 'Dolphin\Ting\Controller\User\GetAdd');
// $app->post('/user/add', 'Dolphin\Ting\Controller\User\PostAdd');
// // 用户列表
// $app->get('/user/records', "Dolphin\Ting\Controller\User\GetRecords");
// // 删除用户
// $app->get('/user/delete/{uuid}', 'Dolphin\Ting\Controller\User\GetDelete');
// // 404
// $app->get('/404', 'Dolphin\Ting\Controller\NotFound');

// // 自动导入
// $app->get('/pic/auto', 'Dolphin\Ting\Controller\Pic\GetAuto');
// // 图片列表
// $app->get('/pic/records', "Dolphin\Ting\Controller\Pic\GetRecords");

// // 标签列表
// $app->get('/mark/records', "Dolphin\Ting\Controller\Mark\GetRecords");


$params = $container->request->getUri()->getPath();

$method = $container->request->getMethod();

$method = strtolower($method);

$class = "Dolphin\Ting\Controller" . "\\";

$path = explode('/', $params);
// 设置默认路由为 Home
$c = isset($path[1]) && $path[1] != '' ? $path[1] : 'Home';

if (isset($path[2]) && $path[2] != '') {
    $class .= ucwords($c) . "\\" . ucwords($method) . ucwords($path[2]);
} else {
    $class .= ucwords($method) . ucwords($c);
}

$app->$method($params, $class);

