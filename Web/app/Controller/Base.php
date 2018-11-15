<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface as ContainerInterface;

class Base
{
    protected $app;
    
    protected $nav;
    // 前端资源存储路径
    private $asset_path;

    function __construct(ContainerInterface $app)
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

        // var_dump($data);

        echo $this->app->template->render($html . '.html', $data);
    }
}

