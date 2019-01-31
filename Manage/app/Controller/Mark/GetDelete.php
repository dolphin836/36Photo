<?php

namespace Dolphin\Ting\Controller\Mark;

use Slim\Http\Request;
use Slim\Http\Response;

class GetDelete extends Mark
{
    public function __invoke(Request $request, Response $response, $args)
    {        
        $uri = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $mark_id = $querys['id'];
        // 开始事务
        $this->app->db->pdo->beginTransaction();

        try {
            // 删除标签
            $this->mark_model->delete(["id" => $mark_id]);
            // 删除图片 - 标签的关联记录
            $this->mark_model->delete_mark_pic($mark_id);
            // 提交事务
            $this->app->db->pdo->commit();
    
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '删除标签成功'
            ]); 
        } catch (\Exception $e) {
            // 回滚事务
            $this->app->db->pdo->rollBack();

            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '删除标签失败'
            ]);
        }

        return $response->withRedirect('/mark/records', 302);
    }
}