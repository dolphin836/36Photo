<?php

namespace Dolphin\Ting\Controller\Recommend;

use Psr\Container\ContainerInterface;
use Dolphin\Ting\Model\Recommend_model;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Constant\Nav;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class Recommend extends \Dolphin\Ting\Controller\Base
{
    protected $recommend_model;

    protected $pic_model;

    protected $oss_client;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

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

        $this->pic_model       = new Pic_model($app);

        $this->recommend_model = new Recommend_model($app);

        $this->nav = Nav::RECOMMEND;
    }
}