<?php

namespace Dolphin\Ting\Controller\Categroy;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class PostAdd extends Categroy
{
    public function __invoke(Request $request, Response $response, $args)
    {   
        $body = $request->getParsedBody();

        if (! $this->validate($body)) {
            return $response->withRedirect('/categroy/add', 302);
        }

        $data = [
            'code' => trim($body['code']),
            'name' => trim($body['name']),
        ];

        $db = $this->categroy_model->add($data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '添加分类失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '添加分类成功'
            ]);  
        }

        return $response->withRedirect('/categroy/records', 302);
    }

    private function validate($body)
    {
        $form_v_error = [];
        // 验证别名
        $error = [];

        if (! isset($body['code']) || $body['code'] === '') {
            $error[] = '别名不得为空.';
        } else {
            if (! v::stringType()->length(1, 16)->validate($body['name'])) {
                $error[] = '别名格式不正确.';
            } else {
                if (! v::stringType()->callback(function($code) {
                    // 别名是否已经存在
                    return ! $this->categroy_model->is_has('code', $code);
                })->validate($body['code'])) {
                    $error[] = '别名已存在.';
                } 
            }
        }

        if (! empty($error)) {
            $form_v_error['code'] = $error;
        }

        // 验证名称
        $error = [];

        if (! isset($body['name']) || $body['name'] === '') {
            $error[] = '名称不得为空.';
        } else {
            if (! v::stringType()->length(1, 32)->validate($body['name'])) {
                $error[] = '名称格式不正确.';
            } else {
                if (v::stringType()->callback(function($name) {
                    // 名称是否已经存在
                    return $this->categroy_model->is_has('name', $name);
                })->validate($body['name'])) {
                    $error[] = '名称已存在.';
                } 
            }
        }

        if (! empty($error)) {
            $form_v_error['name'] = $error;
        }

        if (! empty($form_v_error)) {
            $this->app->flash->addMessage('form_v_error', $form_v_error);

            $this->app->flash->addMessage('form_data', $body);

            return false;
        }

        return true;
    }
}