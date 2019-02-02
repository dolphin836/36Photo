<?php

namespace Dolphin\Ting\Controller\Color;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Exception\NotFoundException;

class GetUnrecommend extends Color
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        if (! isset($querys['color'])) {
            throw new NotFoundException($request, $response);
        }

        $color = $querys['color'];

        $db = $this->color_model->modify(["is_recommend" => 0], ["color" => $color]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '取消推荐失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '取消推荐成功'
            ]);
        }

        return $response->withRedirect('/color/records?search_recommend=1', 302);
    }
}