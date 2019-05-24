<?php

namespace Dolphin\Ting\Controller\Mark;

use Dolphin\Ting\Constant\Common;
use Slim\Http\Request;
use Slim\Http\Response;

class Records extends Mark
{
    /**
     * 热门标签
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
            "is_recommend" => 1,
            "ORDER" => ["count" => "DESC"],
            "LIMIT" => [0, Common::MARK_MAX_COUNT]
        ];

        $marks = $this->mark_model->records($fifter);

        $data = [
            'marks' => $marks,
        ];

        $this->respond('Mark/Records', $data);
    }
}
