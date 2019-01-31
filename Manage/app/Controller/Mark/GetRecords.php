<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Model\Category_model;
use Dolphin\Ting\Constant\Nav;

class GetRecords extends Mark
{
    private $columns = [
        '名称',
        '数量',
        '分类',
        '创建时间'
    ];

    private $sort_item = [
        'count'      => '数量',
        'gmt_create' => '日期'
    ];

    private $category_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav_route = Nav::RECORDS;

        $this->category_model = new Category_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $page   = $request->getAttribute('page');
        $search = $request->getAttribute('search');
        $sort   = $request->getAttribute('sort');
        $order  = $request->getAttribute('order');
        $text   = $request->getAttribute('text');

        $query  = '';

        if (! empty($text)) {
            $query .= '&';
            $query .= http_build_query($text);
        }

        if ($sort != '') {
            $search['ORDER'] = [Table::MARK . "." . $sort => $order];
            $query .= '&sort='  . $sort;
            $query .= '&order=' . $order;
        }

        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];

        $records = $this->mark_model->records($search);

        $data = [
              "records" => $records,
              "columns" => $this->columns,
            "sort_item" => $this->sort_item,
                 "sort" => $sort,
                "order" => $order,
                 "text" => $search,
             "category" => $this->category_model->records(),
                 "page" => Page::reder('/mark/records', $this->mark_model->total($search), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Mark/Records', $data);
    }
}