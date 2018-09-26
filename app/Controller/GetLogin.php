<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;

class GetLogin
{
    private $app;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    public function __invoke($request, $response, $args)
    {   
        if ($this->app->session->exists('uuid')) {
            return $response->withRedirect('/', 302);
        }

        $data = [];

        $data['csrf'] = [
            'name_key' => 'next_name',
           'value_key' => 'next_value',
                'name' => $request->getAttribute('next_name'),
               'value' => $request->getAttribute('next_value')
        ];

        // 页面信息
        $data['site'] = [
            'web_name' => getenv('WEB_NAME')
        ];

        // Flash Data
        // 表单验证错误信息
        if ($this->app->flash->hasMessage('form_v_error')) {
            $data['form_v_error'] = $this->app->flash->getFirstMessage('form_v_error');
        }
        // 表单数据
        if ($this->app->flash->hasMessage('form_data')) {
            $data['form_data'] = $this->app->flash->getFirstMessage('form_data');
        }

        echo $this->app->template->render('Login.html', $data);
    }
}