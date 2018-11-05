<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetRecords extends Categroy
{
    private $columns = [
        '别名',
        '名称',
        '数量',
        '创建时间'
    ];

    public function __invoke($request, $response, $args)
    { 
        // 检索
        $search = $request->getAttribute('search');

        $data = [
              "total" => $this->categroy_model->total(),
            "records" => $this->categroy_model->records($search),
               'text' => $request->getAttribute('text'),
            "columns" => $this->columns
        ];

        $this->respond('Categroy/Records', $data);
    }
}