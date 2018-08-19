<?php

namespace Dolphin\Ting\Controller;

class Login extends Base
{
    public function __invoke($request, $response, $args)
    {   
        $data = [];
        
        $this->respond('Login.html', $data);
    }
}