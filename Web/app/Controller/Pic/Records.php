<?php

namespace Dolphin\Ting\Controller\Pic;

use Dolphin\Ting\Constant\Common;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class Records extends Pic
{
    /**
     * 最新图片
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $page    = isset($args['page']) ? (int) $args['page'] : 1;

        $fifter  = [
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ]; 

        $records = $this->pic_model->records($fifter);

        if (empty($records)) {
            throw new NotFoundException($request, $response);
        }
        // 转换数据格式
        $photos = $this->convert($records);
        // 总数量
        $total  = $this->pic_model->total($fifter);

        $next   = $this->next($total, $page);
        $prev   = $this->prev($total, $page);

        $common = '/photos/';

        $data   = [
             'photos' => $photos,
               'next' => $next ? $common . $next : 'javascript:void(0)',
               'prev' => $prev ? $common . $prev : 'javascript:void(0)',
            'is_show' => false
        ];

        $this->respond('Pic/Records', $data);
    }
}
