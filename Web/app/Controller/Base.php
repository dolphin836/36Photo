<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface;

class Base
{
    protected $app;
    
    protected $nav;
    // 前端资源存储路径
    private $asset_path;
    // 是否支持 WEBP 图片格式
    protected $is_support_webp = false;
    // 时间戳，用于开发环境刷新前端资源
    private $timestamp = 0;

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;

        $this->asset_path = getenv('DEBUG') === 'TRUE' ? '/assets' : '/assets/dist';

        $http_accept = $this->app->request->getHeader('Accept');

        if (strpos($http_accept[0], 'image/webp') !== false) {
            $this->is_support_webp = true;
        }

        getenv('DEBUG') == 'TRUE' ? $this->timestamp = time() : $this->timestamp = 0;
    }

    protected function respond($html, $data = [])
    {
        // 公共数据
        // 页面信息
        $data['site'] = [
              'web_name' => getenv('WEB_NAME'),
                   'nav' => $this->nav,
            'asset_path' => $this->asset_path,
             'timestamp' => $this->timestamp,
        ];

        // var_dump($data);

        echo $this->app->template->render($html . '.html', $data);
    }
}

