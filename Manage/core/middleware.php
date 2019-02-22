<?php
// CSRF
if ($container->request->getUri()->getPath() !== "/pic/upload") { // 排除图片批量上传路由
    $app->add($container->get('csrf'));
}
// 参数获取
$app->add(new Dolphin\Ting\Middleware\Query($container));

$app->add(new Dolphin\Ting\Middleware\Login($container));

$app->add(new \Slim\Middleware\Session([
           'name' => '36photo',
    'autorefresh' => true,
       'lifetime' => '7 day'
]));



