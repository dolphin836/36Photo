<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Nav;

class Discover extends Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::DISCOVER;
    }

    public function __invoke($request, $response, $args)
    {   
        $data = [];
        
        $this->respond('Discover', $data);
    }
}