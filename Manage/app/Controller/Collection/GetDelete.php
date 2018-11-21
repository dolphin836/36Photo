<?php

namespace Dolphin\Ting\Controller\Collection;

use Slim\Http\Request;
use Slim\Http\Response;

class GetDelete extends Collection
{
    public function __invoke(Request $request, Response $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $collection_code = $querys['code'];

        $db = $this->collection_model->delete(["code" => $collection_code]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除专题失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除专题成功'
            ]);  
        }

        return $response->withRedirect('/collection/records', 302);
    }
}