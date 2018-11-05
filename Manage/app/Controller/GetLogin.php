<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetLogin extends Base
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app, '');
    }

    public function __invoke($request, $response, $args)
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