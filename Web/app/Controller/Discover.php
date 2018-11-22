<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class Discover extends Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::DISCOVER;
    }
    public function __invoke(Request $request, Response $response, $args)
    {   
        $data = [];
        
        $this->respond('Discover', $data);
    }
}