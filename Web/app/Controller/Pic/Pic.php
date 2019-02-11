<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface;
use OSS\OssClient as OssClient;
use OSS\Core\OssException as OssException;
use Dolphin\Ting\Constant\Table;
use Dolphin\Ting\Model\Pic_model;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Nav;
use Dolphin\Ting\Librarie\Photo;

class Pic extends \Dolphin\Ting\Controller\Base
{
    protected $oss_client;

    protected $pic_model;
    
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

        $this->pic_model = new Pic_model($app);

        $this->nav = Nav::PICTURE;
    }

    protected function size($size)
    {
        $kb = ceil($size / 1024);

        if ($kb < 1024) {
            return $kb . ' KB';
        }

        $mb = round($kb / 1024, 2);

        return $mb . ' M';
    }

    /**
     * 将数据库查询的多条图片信息记录转换成最终输出的格式
     *
     * @param  array $data
     * @return array
     */
    protected function convert($records, $resize_name = 'PHOTO')
    {
        $photos = [];

        $sma   = $lar = Common::OSS_PROCESS;
        // 本地图片的处理模式
        $photo_mode = Common::PHOTO_LOCAL_MODE;
        $photo_sma  = Common::PHOTO_LOCAL_SMA;
        $photo_lar  = Common::PHOTO_LOCAL_LAR;

        switch ($resize_name) {
            case 'PHOTO': // 图片列表页
                $sma .= Common::PHOTO_RESIZE_SMA;
                $lar .= Common::PHOTO_RESIZE_LAR;
                $photo_mode = Common::PHOTO_LOCAL_MODE;
                $photo_sma  = Common::PHOTO_LOCAL_SMA;
                $photo_lar  = Common::PHOTO_LOCAL_LAR;
                break;
            case 'RECOMMEND': // 推荐页
                $sma .= Common::RECOMMEND_RESIZE_SMA;
                $lar .= Common::RECOMMEND_RESIZE_LAR;
                $photo_mode = Common::RECOMMEND_LOCAL_MODE;
                $photo_sma  = Common::RECOMMEND_LOCAL_SMA;
                $photo_lar  = Common::RECOMMEND_LOCAL_LAR;
                break;    
            default:
                $sma .= Common::PHOTO_RESIZE_SMA;
                $lar .= Common::PHOTO_RESIZE_LAR;
                $photo_mode = Common::PHOTO_LOCAL_MODE;
                $photo_sma  = Common::PHOTO_LOCAL_SMA;
                $photo_lar  = Common::PHOTO_LOCAL_LAR;
                break;
        }

        if ($this->is_support_webp) {
            $sma .= Common::OSS_PROCESS_FORMAT;
            $lar .= Common::OSS_PROCESS_FORMAT; 
        }

        foreach ($records as $record) {
            if ($record['is_oss'] === Common::IS_OSS) {
                $valid = Common::OSS_VALID;

                try {
                    $small = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => $sma
                        ]
                    );

                    $large = $this->oss_client->signUrl(
                        getenv('OSS_BUCKET_NAME'),
                        $record['path'],
                        $valid,
                        "GET",
                        [
                            OssClient::OSS_PROCESS => $lar
                        ]
                    );
                } catch (OssException $e) {
                    $small = Photo::$photo_mode($record['path'], $photo_sma);
                    $large = Photo::$photo_mode($record['path'], $photo_lar);
                }
            } else {
                $small = Photo::$photo_mode($record['path'], $photo_sma);
                $large = Photo::$photo_mode($record['path'], $photo_lar);
            }

            $photos[] = [
                  'hash' => $record['hash'],
                 'large' => $large,
                 'small' => $small,
                 'width' => $record['width'],
                'height' => $record['height']
            ];
        }

        return $photos;
    }

    /**
     * 根据记录总数计算下一页的值
     *
     * @param  int $total
     * @return int
     */
    protected function next($total, $page)
    {
        $page_count = ceil($total / Common::PAGE_COUNT);

        return $page >= $page_count ? 0 : $page + 1;
    }

    /**
     * 根据记录总数计算上一页的值
     *
     * @param  int $total
     * @return int
     */
    protected function prev($total, $page)
    {
        $page_count = ceil($total / Common::PAGE_COUNT);

        return $page <= 1 ? 0 : $page - 1;
    }
}