<?php

namespace Dolphin\Ting\Controller\Collection;

use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;

class GetRecords extends Collection
{
    private $columns = [
        '标题',
        '图片数量',
        '是否公开',
        '推广',
        '创建时间'
    ];

    public function __invoke(Request $request, Response $response, $args)
    { 
        // 分页
        $page   = $request->getAttribute('page');
        // 检索
        $search = $request->getAttribute('search');
        // 排序
        $order  = $request->getAttribute('order');
    
        $query = '&';
    
        if (! empty($search)) {
          $query .= http_build_query($search);
        }
    
        if ($order != '') {
          $query .= '&order=' . $order;
        }
    
        $search['LIMIT'] = [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT];
    
        $search['ORDER'] = ["gmt_create" => $order];
    
        $records = $this->collection_model->records($search);

        $data = [
            "records" => $records,
            "columns" => $this->columns,
               'text' => $request->getAttribute('text'),
               "page" => Page::reder('/collection/records', $this->collection_model->total($search), $page, Common::PAGE_COUNT, $query)
        ];

        $this->respond('Collection/Records', $data);
    }
}