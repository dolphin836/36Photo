<?php

namespace Dolphin\Ting\Controller\Collection;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;

class GetRecords extends Collection
{
    private $columns = [
        '编码',
        '标题',
        '图片数量',
        '是否公开',
        '推广',
        '创建时间'
    ];

    private $sort_item = [
        'gmt_create' => '日期',
        'count'      => '数量',
        'name'       => '标题',
        'browse'     => '热度',
        'collect'    => '收藏'
    ];

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav_route = Nav::RECORDS;
    }

    public function __invoke(Request $request, Response $response, $args)
    { 
        // 分页
        $page   = $request->getAttribute('page');
        // 检索
        $search = $request->getAttribute('search');
        // 排序
        $sort   = $request->getAttribute('sort');
        $order  = $request->getAttribute('order');
        $text   = $request->getAttribute('text');

        $query  = '';
    
        if (! empty($text)) {
            $query .= '&';
            $query .= http_build_query($text);
        }

        if ($sort != '') {
            $search['ORDER'] = [$sort => $order];
            $query .= '&sort='  . $sort;
            $query .= '&order=' . $order;
        }
    
        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];
    
        $records = $this->collection_model->records($search);

        $data = [
              "records" => $records,
              "columns" => $this->columns,
            "sort_item" => $this->sort_item,
                 "sort" => $sort,
                "order" => $order,
                 "text" => $search,
                 "page" => Page::reder('/collection/records', $this->collection_model->total($search), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Collection/Records', $data);
    }
}