<?php

namespace Dolphin\Ting\Controller\Categroy;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Model\Common_model;

class GetRecords extends Categroy
{
    private $common_model;

    private $columns = [
              'code' => '别名',
              'name' => '名称',
             'count' => '数量',
        'gmt_create' => '创建时间'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->common_model = new Common_model($app, $this->table_name);
    }

    public function __invoke($request, $response, $args)
    {  
        $data = [
              "total" => $this->common_model->total(),
            "records" => $this->common_model->records(),
            "columns" => $this->columns
        ];

        $this->respond('Categroy/Records', $data);
    }
}