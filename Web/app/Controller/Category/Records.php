<?php

namespace Dolphin\Ting\Controller\Category;

use Dolphin\Ting\Constant\Common;
use Slim\Http\Request;
use Slim\Http\Response;

class Records extends Category
{
    /**
     * 分类列表
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $fifter = [
            "count[>=]" => 0,
            "ORDER" => ["count" => "DESC"]
        ];

        $records = $this->category_model->records($fifter);

        $data = [
            'category' => $records,
        ];

        $this->respond('Category/Records', $data);
    }
}
