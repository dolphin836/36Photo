<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Ramsey\Uuid\Uuid as U;

class Base
{
    protected $app;
    
    protected $nav;
    // 前端资源存储路径
    private $asset_path;

    function __construct(ContainerInterface $app, $table_name)
    {
        $this->app = $app;

        $this->asset_path = getenv('DEBUG') == 'TRUE' ? '/assets' : '/assets/dist';
    }

    protected function respond($html, $data = [])
    {
        // 公共数据
        // 页面信息
        $data['site'] = [
              'web_name' => getenv('WEB_NAME'),
              'nav_item' => $this->nav,
            'asset_path' => $this->asset_path
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

        // var_dump($data);

        echo $this->app->template->render($html . '.html', $data);
    }

    /**
     * 生成 32 位随机字符串
     */
    protected function random_code()
    {
        return str_replace('-', '', U::uuid4()->toString()); 
    }
}

