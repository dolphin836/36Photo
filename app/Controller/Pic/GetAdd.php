<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetAdd extends Pic
{
    public function __invoke($request, $response, $args)
    {  
        $data = [
            'csrf' => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];
        
        $this->respond('Pic/Add', $data);
    }
}