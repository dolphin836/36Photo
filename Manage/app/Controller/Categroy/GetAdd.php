<?php

namespace Dolphin\Ting\Controller\Categroy;

use Slim\Http\Request;
use Slim\Http\Response;

class GetAdd extends Categroy
{
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

        $this->respond('Categroy/Add', $data);
    }
}