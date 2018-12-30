<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Constant\Table;

class PostModify extends Mark
{
    public function __invoke(Request $request, Response $response, $args)
    {  
        $body = $request->getParsedBody();

        $data = [
            'category_code' => trim($body['category_code'])
        ];

        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $mark_id = $querys['id'];

        $db = $this->mark_model->modify($data, [
            'id' => $mark_id
        ]);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '编辑标签失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '编辑标签成功'
            ]);  
        }

        return $response->withRedirect('/mark/records', 302);
    }
}