<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetAdd extends \Dolphin\Ting\Controller\Base
{
    public function __invoke($request, $response, $args)
    {   
        $this->is_page   = false;

        $this->is_search = false;

        $this->request   = $request;

        $this->respond();
    }
}