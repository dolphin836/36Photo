<?php

namespace Dolphin\Ting\Controller\Mark;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Model\Mark_model;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class Mark extends \Dolphin\Ting\Controller\Base
{
    protected $mark_model;

    protected $oss_client;
    
    function __construct(ContainerInterface $app)
    {
        parent::__construct($app);

        $this->nav = Nav::MARK;

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

        $this->mark_model = new Mark_model($app);
    }
}