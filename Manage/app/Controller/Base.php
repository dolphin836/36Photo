<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;
use Ramsey\Uuid\Uuid as U;

class Base
{
    protected $app;
    
    protected $nav;
    protected $nav_route;
    // 前端资源存储路径
    private $asset_path;
    // 时间戳，用于开发环境刷新前端资源
    private $timestamp = 0;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;

        $this->asset_path = getenv('DEBUG') == 'TRUE' ? '/assets' : '/assets/dist';

        getenv('DEBUG') == 'TRUE' ? $this->timestamp = time() : $this->timestamp = 0;
        // 
        $uri = $this->app->request->getUri();
        $url = $uri->getBaseUrl() . $uri->getPath();

        if ($uri->getQuery() != '') {
            $url .=  '?' . $uri->getQuery();
        }
        
        $this->app->flash->addMessage('redirect-url', $url);
    }

    protected function respond($html, $data = [])
    {
        // 公共数据
        // 页面信息
        $data['site'] = [
              'web_name' => getenv('WEB_NAME'),
                'pc_url' => getenv('PC_URL'),
              'nav_item' => $this->nav,
             'nav_route' => $this->nav_route,
            'asset_path' => $this->asset_path,
             'timestamp' => $this->timestamp
        ];
        // class
        $data['class'] = ['blue', 'azure', 'indigo', 'purple', 'pink', 'orange'];
        // 用户信息
        $data['user'] = [
            'uuid'   => $this->app->session->get('uuid'),
            'name'   => $this->app->session->get('name'),
            'avatar' => getenv('WEB_URL') . '/' . $this->app->session->get('avatar')
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

    /**
     * 转换字节数为 KB、MB
     *
     * @param [type] $size
     * @return string
     */
    protected function size($size)
    {
        $kb = floor($size / 1024);

        if ($kb < 1024) {
            return $kb . ' KB';
        }

        $mb = round($kb / 1024, 2);

        return $mb . ' M';
    }
}

