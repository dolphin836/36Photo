<?php

namespace Dolphin\Ting\Controller\Category;

use Slim\Http\Request;
use Slim\Http\Response;

class GetDelete extends Category
{
    public function __invoke(Request $request, Response $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $category_id = $querys['id'];

        $db = $this->category_model->delete(["id" => $category_id]);

        if (! $db->rowCount()) { // 删除失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除分类失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除分类成功'
            ]);  
        }

        return $response->withRedirect('/category/records', 302);
    }
}