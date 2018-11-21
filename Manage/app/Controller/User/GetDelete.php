<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class GetDelete extends \Dolphin\Ting\Controller\Base
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $db = $this->user_model->delete([
                "uuid" => $args['uuid'],
            "group[!]" => 2
        ]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除用户失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除用户成功'
            ]);  
        }

        return $response->withRedirect('/user/records', 302);
    }
}