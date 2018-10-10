<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Model\Common_model;

class GetRecords extends Categroy
{
    private $common_model;

    private $columns = [
        '别名',
        '名称',
        '数量',
        '创建时间'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->common_model = new Common_model($app, $this->table_name);
    }

    public function __invoke($request, $response, $args)
    { 
        // 检索
        $search = $request->getAttribute('search');

        $data = [
              "total" => $this->common_model->total(),
            "records" => $this->common_model->records($search),
               'text' => $request->getAttribute('text'),
            "columns" => $this->columns
        ];

        $this->respond('Categroy/Records', $data);
    }
}