<?php

namespace Dolphin\Ting\Controller\Collection;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;

class GetModify extends Collection
{
    public function __invoke(Request $request, Response $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (! isset($querys['code'])) {
            throw new NotFoundException($request, $response);
        }

        $collection_code = $querys['code'];

        $collection = $this->collection_model->record([
            "code" => $collection_code
        ]);

        $data = [
                "collection" => $collection,
                      "csrf" => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $this->respond('Collection/Modify', $data);
    }
}