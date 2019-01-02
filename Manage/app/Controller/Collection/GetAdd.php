<?php

namespace Dolphin\Ting\Controller\Collection;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class GetAdd extends Collection
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav_route = Nav::ADD;
    }
    
    public function __invoke(Request $request, Response $response, $args)
    {   
        $data = [
            'csrf' => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $this->respond('Collection/Add', $data);
    }
}