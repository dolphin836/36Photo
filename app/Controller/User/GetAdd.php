<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetAdd extends \Dolphin\Ting\Controller\Base
{
    public function __invoke($request, $response, $args)
    {   
        // CSRF
        $this->is_csrf    = true;
        $this->csrf_name  = $request->getAttribute('next_name');
        $this->csrf_value = $request->getAttribute('next_value');

        $data = [];

        $this->respond('User\Add.html', $data);
    }
}