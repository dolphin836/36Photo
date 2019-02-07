<?php

namespace Dolphin\Ting\Controller\Color;

use Slim\Http\Request;
use Slim\Http\Response;

class Records extends Color
{
    /**
     * 热门颜色
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $colors = $this->color_model->records([
            'is_recommend' => 1
        ]);

        $data = [
            'colors' => array_column($colors, 'color'),
        ];

        $this->respond('Color/Records', $data);
    }
}
