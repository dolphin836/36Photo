<?php

namespace Dolphin\Ting\Controller\User;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetDelete extends \Dolphin\Ting\Controller\Base
{
    public function __invoke($request, $response, $args)
    {   
        $db = $this->app->db->delete("user", [
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