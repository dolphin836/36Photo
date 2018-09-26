<?php
// CSRF
$app->add($container->get('csrf'));
// 表单验证
// $app->add(new Dolphin\Ting\Middleware\Validation($container));
// 参数获取
$app->add(new Dolphin\Ting\Middleware\Query($container));

$app->add(new Dolphin\Ting\Middleware\Login($container));

$app->add(new \Slim\Middleware\Session([
           'name' => 'Emage_Session',
    'autorefresh' => true,
       'lifetime' => '7 day'
]));



