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

        $category = $this->category_model->record(['id' => $category_id]);

        // 开始事务
        $this->app->db->pdo->beginTransaction();

        try {
            // 删除分类
            $this->category_model->delete(["id" => $category_id]);

            // 更新相关图片的分类为默认分类
            $this->pic_model->modify([
                'category_code' => 'default'
            ], [
                'category_code' => $category['code']
            ]);
            // 更新相关标签的分类为默认分类
            $this->mark_model->modify([
                'category_code' => 'default'
            ], [
                'category_code' => $category['code']
            ]);
            
            // 提交事务
            $this->app->db->pdo->commit();
    
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除分类成功'
            ]); 
        } catch (Exception $e) {
            // 回滚事务
            $this->app->db->pdo->rollBack();

            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除分类失败'
            ]);

        }

        return $response->withRedirect('/category/records', 302);
    }
}