<?php
// CSRF
$app->add($container->get('csrf'));
// 表单验证
$app->add(new Dolphin\Ting\Middleware\Validation($container));
// 参数获取
$app->add(new Dolphin\Ting\Middleware\Query($container));

