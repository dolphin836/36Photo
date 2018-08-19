<?php

namespace Dolphin\Ting\Controller;

class Home extends Base
{
    public function __invoke($request, $response, $args)
    {   
        $data = [];
        
        $this->respond('Home.html', $data);
    }
}