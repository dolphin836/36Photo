<?php

namespace Dolphin\Ting\Controller\Mark;

use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class PostAdd extends Mark
{
    public function __invoke(Request $request, Response $response, $args)
    {  
        $body = $request->getParsedBody();

        if (! $this->validate($body)) {
            return $response->withRedirect('/mark/add', 302);
        }

        $data = [
            'name'          => trim($body['name']),
            'category_code' => trim($body['category_code'])
        ];

        $db = $this->mark_model->add($data);

        if (! $db->rowCount()) { // 插入失败
            $this->app->flash->addMessage('note', [
                'code' => 'danger',
                'text' => '新增标签失败'
            ]);
        } else {
            $this->app->flash->addMessage('note', [
                'code' => 'success',
                'text' => '新增标签成功'
            ]);  
        }

        return $response->withRedirect('/mark/records', 302);
    }

    private function validate($body)
    {
        $form_v_error = [];
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
                    return $this->mark_model->is_has('name', $name);
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