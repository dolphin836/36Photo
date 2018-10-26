<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetAdd extends Pic
{
    public function __invoke($request, $response, $args)
    {  
        $data = [];
        
        $this->respond('Pic/Add', $data);
    }
}