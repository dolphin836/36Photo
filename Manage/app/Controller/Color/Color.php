<?php

namespace Dolphin\Ting\Controller\Color;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Color_model;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class Color extends \Dolphin\Ting\Controller\Base
{
    protected $color_model;

    protected $oss_client;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::COLOR;

        try {
            $this->oss_client = new OssClient(
                getenv('OSS_ACCESS_KEY_ID'),
                getenv('OSS_ACCESS_SECRET'),
                getenv('OSS_END_POINT')
            );
        } catch (OssException $e) {
            printf(__FUNCTION__ . "阿里云 OSS 初始化失败。\n");
            printf($e->getMessage() . "\n");
            exit();
        }

        $this->color_model = new Color_model($app);
    }
}