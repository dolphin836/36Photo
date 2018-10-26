<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;

class GetRecords extends Mark
{
    private $columns = [
        '名称',
        '数量',
        '创建时间'
    ];

    public function __invoke($request, $response, $args)
    {  
        $page = $request->getAttribute('page');

        $records = $this->common_model->records([
            "LIMIT" => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT],
            "ORDER" => ["count" => "DESC"]
        ]);

        $data = [
            "records" => $records,
            "columns" => $this->columns,
               "page" => Page::reder('/mark/records', $this->common_model->total(), $page, Common::PAGE_COUNT, '')
        ];

        $this->respond('Mark/Records', $data);
    }
}