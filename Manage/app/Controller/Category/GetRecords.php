<?php

namespace Dolphin\Ting\Controller\Category;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class GetRecords extends Category
{
    private $columns = [
        '别名',
        '名称',
        '数量',
        '创建时间'
    ];

    private $sort_item = [
        'count'        => '数量',
        'gmt_create'   => '日期',
        'is_recommend' => '推荐'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav_route = Nav::RECORDS;
    }

    public function __invoke(Request $request, Response $response, $args)
    { 
        // 检索
        $search = $request->getAttribute('search');
        $sort   = $request->getAttribute('sort');
        $order  = $request->getAttribute('order');

        if ($sort != '') {
            $search['ORDER'] = [$sort => $order];
        }

        $data = [
                "total" => $this->category_model->total(),
              "records" => $this->category_model->records($search),
                 'text' => $search,
            "sort_item" => $this->sort_item,
                 "sort" => $sort,
                "order" => $order,
              "columns" => $this->columns
        ];

        $this->respond('Category/Records', $data);
    }
}