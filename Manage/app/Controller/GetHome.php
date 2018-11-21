<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class GetHome extends Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::HOME;
    }

    public function __invoke(Request $request, Response $response, $args)
    {   
        $data = [];
        
        $this->respond('Home', $data);
    }
}