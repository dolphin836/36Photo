<?php

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

