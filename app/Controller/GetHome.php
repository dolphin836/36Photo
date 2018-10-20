<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetHome extends Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = 'home';
    }

    public function __invoke($request, $response, $args)
    {   
        $data = [];
        
        $this->respond('Home', $data);
    }
}