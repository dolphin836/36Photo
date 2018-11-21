<?php

namespace Dolphin\Ting\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class Discover extends Base
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $data = [];
        
        $this->respond('Discover', $data);
    }
}