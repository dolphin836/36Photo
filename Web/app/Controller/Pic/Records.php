<?php

namespace Dolphin\Ting\Controller\Pic;

use Psr\Container\ContainerInterface as ContainerInterface;
use Dolphin\Ting\Constant\Common;
use Dolphin\Ting\Constant\Table;
use OSS\OssClient;
use OSS\Core\OssException;
use Slim\Exception\NotFoundException;

class Records extends Pic
{
    /**
     * 最新图片
     *
     * @param object $request  HTTP 请求对象
     * @param object $response HTTP 响应对象
     * @param array  $args     HTTP 请求参数
     * 
     * @return void
     */
    public function __invoke($request, $response, $args)
    { 
        $page    = isset($args['page']) ? (int) $args['page'] : 1;

        $fifter  = [
            'LIMIT' => [Common::PAGE_COUNT * ($page - 1), Common::PAGE_COUNT]
        ]; 

        // 生产环境展示已上传阿里云的图片
        if (getenv('DEBUG') === 'FALSE') {
            $fifter[Table::PICTURE . '.is_oss'] = 1;
        }

        $records = $this->pic_model->records($fifter);

        if (empty($records)) {
            throw new NotFoundException($request, $response);
        }

        $photos  = [];

        $sma   = $lar = Common::OSS_PROCESS;
        $sma  .= Common::OSS_PROCESS_RESIZE_320;
        $lar  .= Common::OSS_PROCESS_RESIZE_640;

        if ($this->is_support_webp) {
            $sma .= Common::OSS_PROCESS_FORMAT;
            $lar .= Common::OSS_PROCESS_FORMAT; 
        }

        foreach ($records as $record) {
            if ($record['is_oss']) {
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
                    $large = $small = getenv('WEB_URL') . '/' . $record['path'];
                }
            } else {
                $large = $small = getenv('WEB_URL') . '/' .$record['path'];
            }

            $photos[] = [
                  'hash' => $record['hash'],
                 'large' => $large,
                 'small' => $small,
                 'width' => $record['width'],
                'height' => $record['height']
            ];
        }
        // 总数量
        $total = $this->pic_model->total($fifter);

        $page_count = ceil($total / Common::PAGE_COUNT);

        $next = $page >= $page_count ? 0 : $page + 1;
        $prev = $page <= 1 ? 0 : $page - 1;

        $data = [
            'photos' => $photos,
              'next' => $next,
              'prev' => $prev
        ];

        $this->respond('Pic/Records', $data);
    }
}
