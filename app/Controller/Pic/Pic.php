<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;

class Pic extends \Dolphin\Ting\Controller\Base
{
    protected $table_name = Table::PICTURE;

    protected $conf = [
        
    ];

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

        $this->nav = 'pic';
    }
}