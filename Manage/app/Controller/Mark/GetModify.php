<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Model\Category_model;

class GetModify extends Mark
{
    private $category_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->category_model = new Category_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $mark_id = $querys['id'];

        $mark = $this->mark_model->record([
            Table::MARK . ".id" => $mark_id
        ]);

        $data = [
                "mark" => $mark,
            "category" => $this->category_model->records(),
                "csrf" => [
                'name_key' => 'next_name',
               'value_key' => 'next_value',
                    'name' => $request->getAttribute('next_name'),
                   'value' => $request->getAttribute('next_value')
            ]
        ];

        $this->respond('Mark/Modify', $data);
    }
}