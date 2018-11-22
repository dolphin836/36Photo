<?php

$app->get('/', "Dolphin\Ting\Controller\Discover");
// 图片列表页
$app->get('/photo/{hash:[0-9a-z]{10,16}+}', 'Dolphin\Ting\Controller\Pic\Record');
// 图片详情页
$app->get('/photos[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Records');
// 下载图片
$app->get('/photo/d/{hash:[0-9a-z]{10,16}+}', 'Dolphin\Ting\Controller\Pic\D');
// 某颜色的图片列表页
$app->get('/color/{color:[0-9a-f]{6}+}[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Color');
// 某标签的图片列表页
$app->get('/mark/{mark}[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Mark');
// 随机图片列表页
$app->get('/random[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Random');
// 标签聚合页
$app->get('/marks', 'Dolphin\Ting\Controller\Mark\Records');
// 颜色聚合页
$app->get('/colors', 'Dolphin\Ting\Controller\Color\Records');

