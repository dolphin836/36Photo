<?php

namespace Dolphin\Ting\Controller\Category;

use Slim\Http\Request;
use Slim\Http\Response;

class GetModify extends Category
{
    public function __invoke(Request $request, Response $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $category_id = $querys['id'];

        $data = [
            "category" => $this->category_model->record(['id' => $category_id]),
                "csrf" => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $this->respond('Category/Modify', $data);
    }
}