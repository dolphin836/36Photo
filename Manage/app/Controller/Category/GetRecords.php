<?php

namespace Dolphin\Ting\Controller\Category;

use Slim\Http\Request;
use Slim\Http\Response;

class GetRecords extends Category
{
    private $columns = [
        '别名',
        '名称',
        '数量',
        '创建时间'
    ];

    public function __invoke(Request $request, Response $response, $args)
    { 
        // 检索
        $search = $request->getAttribute('search');

        $data = [
              "total" => $this->category_model->total(),
            "records" => $this->category_model->records($search),
               'text' => $request->getAttribute('text'),
            "columns" => $this->columns
        ];

        $this->respond('Category/Records', $data);
    }
}