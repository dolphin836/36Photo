<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use Slim\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Nav;

class Category extends Pic
{
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::CATEGORY;
    }
    /**
     * 按分类查询图片记录
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    { 
        $category_code = $args['code'];

        $page  = isset($args['page']) ? (int) $args['page'] : 1;

        $fifter = [
            'category' => $category_code,
               'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ];
        // 总数量
        $total   = $this->pic_model->total($fifter);

        $records = $this->pic_model->records($fifter);

        if (empty($records)) {
            throw new NotFoundException($request, $response);
        }
        // 转换数据格式
        $photos = $this->convert($records);
        // 上下页
        $next   = $this->next($total, $page);
        $prev   = $this->prev($total, $page);

        $common = '/category/' . $category_code . '/';

        $data   = [
             'photos' => $photos,
               'next' => $next ? $common . $next : 'javascript:void(0)',
               'prev' => $prev ? $common . $prev : 'javascript:void(0)',
            'is_show' => true
        ];

        $this->respond('Pic/Records', $data);
    }
}
