<?php

namespace Dolphin\Ting\Controller;

class NotFound extends Base
{
    public function __invoke($request, $response, $args)
    {   
        echo $this->app->template->render('404.html');
    }
}