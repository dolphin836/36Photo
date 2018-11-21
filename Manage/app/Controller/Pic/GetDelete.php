<?php

namespace Dolphin\Ting\Controller\Pic;

use Slim\Http\Request;
use Slim\Http\Response;

class GetDelete extends Pic
{
    public function __invoke(Request $request, Response $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $hash = $querys['hash'];

        $pic  = $this->pic_model->record(["hash" => $hash]);

        $db   = $this->pic_model->delete(["hash" => $hash]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除图片失败'
            ]);
        } else {
            // 删除本地文件
            unlink($pic['path']);

            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除图片成功'
            ]);
        }

        return $response->withRedirect('/pic/records', 302);
    }
}