<?php

$app->get('/photo/{hash}', 'Dolphin\Ting\Controller\Pic\Record');

$app->get('/photos[/{page:[0-9]+}]', 'Dolphin\Ting\Controller\Pic\Records');

