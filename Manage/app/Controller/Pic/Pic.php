<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Pic_model;

class Pic extends \Dolphin\Ting\Controller\Base
{
    protected $oss_client;

    protected $pic_model;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::PICTURE;

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

        $this->pic_model = new Pic_model($app);
    }
}