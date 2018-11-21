<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class PostLogin
{
    private $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke(Request $request, Response $response, $args)
    {   
        $body = $request->getParsedBody();

        if (! $this->validate($body)) {
            return $response->withRedirect('/login', 302);
        }

        return $response->withRedirect('/', 302);
    }

    private function validate($body)
    {
        $form_v_error = [];
        // 验证邮箱格式
        $error = [];

        if (! isset($body['email']) || $body['email'] === '') {
            $error[] = '邮箱不得为空.';
        } else {
            if (! v::email()->length(1, 255)->validate($body['email'])) {
                $error[] = '不能识别的邮箱地址.';
            } else {
                if (! v::email()->callback(function($email) {
                    // 邮箱是否已经存在
                    return $this->app->db->has("user", [
                           "email" => $email,
                        "group[!]" => 0
                    ]);
                })->validate($body['email'])) {
                    $error[] = '邮箱不存在.';
                } 
            }
        }

        if (! empty($error)) {
            $form_v_error['email'] = $error;
        }
        //验证密码格式
        $error = [];

        if (! isset($body['password']) || $body['password'] === '') {
            $error[] = '密码不得为空.';
        } else {
            if (! v::stringType()->notEmpty()->noWhitespace()->length(8, 32)->validate($body['password'])) {
                $error[] = '密码 的有效长度为 8 ~ 32 位.';
            }
        }

        if (! empty($error)) {
            $form_v_error['password'] = $error;
        } else {
            // 验证密码是否正确
            $user = $this->app->db->get("user", [
                "uuid",
                "name",
                "email",
                "password"
            ], [
                "email" => $body['email']
            ]);

            if (! password_verify($body['password'], $user['password'])) {
                $form_v_error['password'] = ['密码不正确.'];
            } else {
                $this->app->session->set('uuid', $user['uuid']);
                $this->app->session->set('name', $user['name']);
            }
        }

        if (! empty($form_v_error)) {
            $this->app->flash->addMessage('form_v_error', $form_v_error);
            // 移除密码
            unset($body['password']);

            $this->app->flash->addMessage('form_data', $body);

            return false;
        }

        return true;
    }
}