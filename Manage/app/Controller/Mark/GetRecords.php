<?php

namespace Dolphin\Ting\Controller\Mark;

use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Librarie\Page;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;

class GetRecords extends Mark
{
    private $columns = [
        '名称',
        '数量',
        '分类',
        '创建时间'
    ];

    public function __invoke(Request $request, Response $response, $args)
    {  
        $page = $request->getAttribute('page');

        $records = $this->mark_model->records([
            "LIMIT" => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT],
            "ORDER" => [Table::MARK . ".count" => "DESC"]
        ]);

        $data = [
            "records" => $records,
            "columns" => $this->columns,
               "page" => Page::reder('/mark/records', $this->mark_model->total(), $page, Common::PAGE_COUNT, '')
        ];

        $this->respond('Mark/Records', $data);
    }
}