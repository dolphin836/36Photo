<?php

namespace Dolphin\Ting\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class GetLogin extends Base
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        if ($this->app->session->exists('uuid')) {
            return $response->withRedirect('/', 302);
        }

        $data = [
            'csrf' => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $this->respond('Login', $data);
    }
}