<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Ramsey\Uuid\Uuid as U;

class Base
{
    protected $app;
    // 每页显示的记录数量
    protected $count = 20;
    protected $is_csrf    = false;
    protected $csrf_name  = '';
    protected $csrf_value = '';
    // 用户组
    protected $user_groups = [
        '普通用户',
        '管理员',
        '超级管理员'
    ];
    // 用户来源
    protected $user_client = [
        '后台',
        'PC',
        '移动',
        'iOS',
        'Android',
        '微信小程序'
    ];

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    protected function respond($template = '', $data = [])
    {
        // 页面信息
        $data['site'] = [
            'web_name' => getenv('WEB_NAME')
        ];
        // 表单验证错误信息
        if ($this->app->flash->hasMessage('form_v_error')) {
            $data['form_v_error'] = $this->app->flash->getFirstMessage('form_v_error');
        }
        // 表单数据
        if ($this->app->flash->hasMessage('form_data')) {
            $data['form_data'] = $this->app->flash->getFirstMessage('form_data');
        }
        // CSRF
        if ($this->is_csrf) {
            $data['csrf'] = [
                 'name_key' => 'next_name',
                'value_key' => 'next_value',
                     'name' => $this->csrf_name,
                    'value' => $this->csrf_value
            ];
        }
        // 系统消息
        if ($this->app->flash->hasMessage('note')) {
            $data['note'] = $this->app->flash->getFirstMessage('note');
        }

        // var_dump($data);

        echo $this->app->template->render($template, $data);
    }

    protected function random_code()
    {
        return str_replace('-', '', U::uuid4()->toString()); 
    }
}

