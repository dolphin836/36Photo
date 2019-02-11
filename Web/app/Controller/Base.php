<?php

namespace Dolphin\Ting\Controller;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Constant\Lang;

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
    // 语言，默认英语，
    private $lang = 'en';
    // 已经支持的语言 zh、en
    private $support_lang = [
        'en' => 'English',
        'zh' => '简体中文'
    ];
    // 需要载入的语言配置，Common 默认都会载入
    protected $lang_module = [
        Lang::COMMON
    ];

    function __construct(ContainerInterface $app)
    {
        $this->app = $app;
        // 设置前端资源根目录
        $this->asset_path = getenv('DEBUG') === 'TRUE' ? '/assets' : '/assets/dist';
        // 设置浏览器是否支持 webp 格式的图片
        $http_accept = $this->app->request->getHeader('Accept');

        if (strpos($http_accept[0], 'image/webp') !== false) {
            $this->is_support_webp = true;
        }
        // 设置默认语言
        // 先从 COOKIE 中读取配置
        if (isset($_COOKIE['Lang']) && array_key_exists($_COOKIE['Lang'], $this->support_lang)) {
            $this->lang = $_COOKIE['Lang'];
        } else { // 再读取浏览器的语言设置
            $http_accept_lang = $this->app->request->getHeader('Accept-Language');

            $lang_code = substr($http_accept_lang[0], 0, 2);
    
            if (array_key_exists($lang_code, $this->support_lang)) {
                $this->lang = $lang_code;
            }
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
        // 语言
        $data['lang'] = [
                'langs' => $this->support_lang,
            'lang_code' => $this->lang
        ];

        foreach ($this->lang_module as $module_name) {
            $data['lang'][$module_name] = require LANGPATH . ucwords($this->lang) . DIRECTORY_SEPARATOR . ucwords($module_name) . '.php';
        }

        // var_dump($data);

        echo $this->app->template->render($html . '.twig', $data);
    }
}

