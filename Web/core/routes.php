<?php

$app->get('/', "Dolphin\Ting\Controller\Discover");

$app->get('/photo/{hash:[0-9a-z]{10,16}+}', 'Dolphin\Ting\Controller\Pic\Record');

$app->get('/photos[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Records');

$app->get('/photos/color/{color:[0-9a-f]{6}+}[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Color');

$app->get('/photo/d/{hash:[0-9a-z]{10,16}+}', 'Dolphin\Ting\Controller\Pic\D');

