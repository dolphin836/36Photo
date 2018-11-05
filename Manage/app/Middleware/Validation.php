<?php

namespace Dolphin\Ting\Middleware;

use Psr\Container\ContainerInterface as ContainerInterface;
use Respect\Validation\Validator as v;

class Validation
{
    protected $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke($request, $response, $next)
    {
        if ($request->isPost()) {
            $body = $request->getParsedBody();

            foreach ($body as $name => $value) {
                $error = [];
                // 姓名
                if ($name == 'name' && $value != '') {
                    if (! v::stringType()->length(2, 32)->validate($value)) {
                        $error[] = '姓名 的有效长度为 2 ~ 32 位.';
                    }

                    if (! empty($error)) {
                        $form_v_error['name'] = $error;
                    }
                }
                // 用户名
                if ($name == 'username') {
                    if (! v::alnum('_')->notEmpty()->noWhitespace()->length(2, 32)->validate($value)) {
                        $error[] = '用户名 只能是 2 ~ 32 位的大小写英文字符、阿拉伯数字和下划线.';
                    } else {
                        if (! v::alnum()->callback(function($username) {
                            // 用户名是否已经存在
                            return ! $this->app->db->has("user", [
                                "username" => $username
                            ]);
                        })->validate($value)) {
                            $error[] = '用户名 已经存在.';
                        }
                    }

                    if (! empty($error)) {
                        $form_v_error['username'] = $error;
                    }
                }
                // 手机号
                if ($name == 'phone') {
                    if (! v::intVal()->notEmpty()->length(11, 11)->min(1)->validate($value)) {
                        $error[] = '手机号 只能是 11 位的阿拉伯数字.';
                    } else {
                        if (! v::intVal()->callback(function($phone) {
                            // 手机号是否已经存在
                            return ! $this->app->db->has("user", [
                                "phone" => $phone
                            ]);
                        })->validate($value)) {
                            $error[] = '手机号 已经存在.';
                        }
                    }

                    if (! empty($error)) {
                        $form_v_error['phone'] = $error;
                    }
                }
                // 邮箱
                if ($name == 'email' && $value != '') {
                    if (! v::email()->length(1, 255)->validate($value)) {
                        $error[] = '不能识别的邮箱地址.';
                    } else {
                        if (! v::email()->callback(function($email) {
                            // 邮箱是否已经存在
                            return ! $this->app->db->has("user", [
                                "email" => $email
                            ]);
                        })->validate($value)) {
                            $error[] = '邮箱 已经存在.';
                        } 
                    }

                    if (! empty($error)) {
                        $form_v_error['email'] = $error;
                    }
                }
                // 昵称
                if ($name == 'nickname' && $value != '') {
                    if (! v::stringType()->length(2, 32)->validate($value)) {
                        $error[] = '昵称 只能是 2 ~ 32 位的字符中、英文字符或阿拉伯数字.';
                    }

                    if (! empty($error)) {
                        $form_v_error['nickname'] = $error;
                    }
                }
                // 密码
                if ($name == 'password') {
                    if (! v::stringType()->notEmpty()->noWhitespace()->length(8, 32)->validate($value)) {
                        $error[] = '密码 的有效长度为 8 ~ 32 位.';
                    }

                    if (! empty($error)) {
                        $form_v_error['password'] = $error;
                    }
                }
                // 签名
                if ($name == 'sign' && $value != '') {
                    if (! v::stringType()->length(4, 128)->validate($value)) {
                        $error[] = '签名 的有效长度为 4 ~ 128 位.';
                    }

                    if (! empty($error)) {
                        $form_v_error['sign'] = $error;
                    }
                }
            }

            if (! empty($form_v_error)) {
                $this->app->flash->addMessage('form_v_error', $form_v_error);
                $this->app->flash->addMessage('form_data', $body);

                return $response->withRedirect('/user/add', 302);
            }
        }

        $response = $next($request, $response);

        return $response;
    }
}