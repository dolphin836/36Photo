<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;

class Base
{
    protected $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    protected function respond($html, $data = [])
    {
        // 公共数据
        // 页面信息
        $data['site'] = [
            'web_name' => getenv('WEB_NAME')
        ];
        // class
        $data['class'] = ['blue', 'azure', 'indigo', 'purple', 'pink', 'orange'];

        // Flash Data
        // 表单验证错误信息
        if ($this->app->flash->hasMessage('form_v_error')) {
            $data['form_v_error'] = $this->app->flash->getFirstMessage('form_v_error');
        }
        // 表单数据
        if ($this->app->flash->hasMessage('form_data')) {
            $data['form_data'] = $this->app->flash->getFirstMessage('form_data');
        }
        // 系统消息
        if ($this->app->flash->hasMessage('note')) {
            $data['note'] = $this->app->flash->getFirstMessage('note');
        }

        echo $this->app->template->render($html . '.html', $data);
    }
}

