<?php

namespace Dolphin\Ting\Controller\Category;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Model\Mark_model;
use Respect\Validation\Validator as v;

class PostModify extends Category
{
    private $pic_model;

    private $mark_model;

    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->pic_model  = new Pic_model($app);

        $this->mark_model = new Mark_model($app);
    }

    public function __invoke(Request $request, Response $response, $args)
    {  
        $body = $request->getParsedBody();

        $uri  = $request->getUri();

        parse_str($uri->getQuery(), $querys);

        $category_id = $querys['id'];

        $data = [
                    'code' => trim($body['code']),
                    'name' => trim($body['name']),
            'is_recommend' => $body['is_recommend']
        ];

        $category_code = trim($body['code']);
        $category_name = trim($body['name']);

        $category = $this->category_model->record(['id' => $category_id]);
        // 校验别名
        if ($category_code != $category['code'] && ! $this->validate_code($category_code)) {
            return $response->withRedirect('/category/modify?id=' . $category_id, 302);
        }
        // 校验名称
        if ($category_name != $category['name'] && ! $this->validate_code($category_name)) {
            return $response->withRedirect('/category/modify?id=' . $category_id, 302);
        }
        // 开始事务
        $this->app->db->pdo->beginTransaction();

        try {
            // 更新分类
            $this->category_model->modify($data, [
                'id' => $category_id
            ]);

            if ($category_code != $category['code']) {
                // 更新图片的分类
                $this->pic_model->modify([
                    'category_code' => $category_code
                ], [
                    'category_code' => $category['code']
                ]);
                // 更新标签的分类
                $this->mark_model->modify([
                    'category_code' => $category_code
                ], [
                    'category_code' => $category['code']
                ]);
            }

            // 提交事务
            $this->app->db->pdo->commit();
   
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '保存成功'
            ]); 
        } catch (Exception $e) {
            // 回滚事务
            $this->app->db->pdo->rollBack();

            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '保存失败'
            ]);

        }

        return $response->withRedirect('/category/modify?id=' . $category_id, 302);
    }

    private function validate_code($code)
    {
        $form_v_error = [];
        // 验证别名
        $error = [];

        if ($code === '') {
            $error[] = '别名不得为空.';
        } else {
            if (! v::stringType()->length(1, 16)->validate($code)) {
                $error[] = '别名格式不正确.';
            } else {
                if (! v::stringType()->callback(function($code) {
                    // 别名是否已经存在
                    return ! $this->category_model->is_has('code', $code);
                })->validate($code)) {
                    $error[] = '别名已存在.';
                } 
            }
        }

        if (! empty($error)) {
            $form_v_error['code'] = $error;
        }

        if (! empty($form_v_error)) {
            $this->app->flash->addMessage('form_v_error', $form_v_error);

            $this->app->flash->addMessage('form_data', ['code' => $code]);

            return false;
        }

        return true;
    }

    private function validate_name($name)
    {
        $form_v_error = [];
        // 验证名称
        $error = [];

        if ($name === '') {
            $error[] = '名称不得为空.';
        } else {
            if (! v::stringType()->length(1, 32)->validate($name)) {
                $error[] = '名称格式不正确.';
            } else {
                if (v::stringType()->callback(function($name) {
                    // 名称是否已经存在
                    return $this->category_model->is_has('name', $name);
                })->validate($name)) {
                    $error[] = '名称已存在.';
                } 
            }
        }

        if (! empty($error)) {
            $form_v_error['name'] = $error;
        }

        if (! empty($form_v_error)) {
            $this->app->flash->addMessage('form_v_error', $form_v_error);

            $this->app->flash->addMessage('form_data', ['name' => $name]);

            return false;
        }

        return true;
    }
}